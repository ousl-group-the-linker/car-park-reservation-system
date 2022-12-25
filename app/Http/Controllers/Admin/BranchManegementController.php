<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class BranchManegementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $data = (object)$request->validate([
            "email" => "nullable|email",
            "address_city" => "nullable",
        ]);

        $branches = Branch::query();

        if (($data->email ?? NULL) != null) {
            $branches->where("email", $data->email);
        }
        if (($data->address_city ?? NULL) != null) {
            $branches->where("address_city_id", $data->address_city);
        }

        $branches = $branches->orderBy("created_at", "DESC")
            ->paginate(20);

        $districts = SriLankaDistrict::all();

        return view("admin.branch-management.index", [
            "districts" => $districts,
            "branches" => $branches
        ]);
    }

    /**
     * Show create branch page
     */
    public function showNew(Request $request)
    {
        $districts = SriLankaDistrict::all();


        return view("admin.branch-management.new", [
            "districts" => $districts
        ]);
    }

    /**
     * Save new branch
     */
    public function saveNew(Request $request)
    {
        $this->authorize("create", Branch::class);

        $data = (object)$request->validate([
            "name" => "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "email" => "required|email",
            "contact_number" => "required|numeric|digits:10",
            "parking_slots" => "required|integer|min:1",
            "hourly_rate" => "required|integer|min:0",
            "manager_id" => [
                "nullable", "integer",
                Rule::exists('users', "id")->where(function ($query) {
                    return $query->where('role', User::$ROLE_MANAGER);
                }),
                Rule::unique("branches", "manager_id"),
            ]
        ], [
            "manager_id.exists" => "The selected manager is not a valid manager or the account does not exist.",
            "manager_id.unique" => "Selected manager is already associated with another branch.",
            "manager_id.*" => "The manager field is invalid.",
        ], [
            "manager_id" => "manager"
        ]);


        Branch::create([
            "name" => $data->name,
            "address_line_1" => $data->address_line_1,
            "address_line_2" => $data->address_line_2,
            "address_line_3" => $data->address_line_3,
            "address_city_id" => $data->address_city,
            "email" => $data->email,
            "contact_number" => $data->contact_number,
            "parking_slots" => $data->parking_slots,
            "manager_id" => $data->manager_id,
            "hourly_rate" => $data->hourly_rate
        ]);

        return redirect()->route('branches-management.new')
            ->with(["branch-update-or-create-success-message" => "New branch is successfully created."]);
    }


    /**
     * Search managers (json)
     */
    public function searchManagers(Request $request)
    {
        if (strlen($request->input("name")) < 1 && strlen($request->input("email")) < 1) {
            return response()->json(new Collection());
        }


        $managers = User::where("role", User::$ROLE_MANAGER);

        if (strlen($request->input("name")) > 0) {
            $managers->where(DB::raw("CONCAT(first_name, ' ', last_name)"), "like", "%" . $request->input("name") . "%");
        }

        if (strlen($request->input("email")) > 0) {
            $managers->where("email", $request->input("email"));
        }


        $managers = $managers->limit(10)->get();

        $managers = $managers->map(function ($manager) {
            $manager->city = $manager->City;
            $manager->branch = $manager->Branch;
            return $manager;
        });

        return response()->json($managers);
    }

    /**
     * Show admin branch edit page
     */
    public function showExtraView(Branch $branch, Request $request)
    {
        $districts = SriLankaDistrict::all();

        return view("admin.branch-management.view-extra", [
            "districts" => $districts,
            "branch" => $branch
        ]);
    }

    /**
     * Show admin branch edit page
     */
    public function showEdit(Branch $branch, Request $request)
    {
        $districts = SriLankaDistrict::all();

        return view("admin.branch-management.edit", [
            "districts" => $districts,
            "branch" => $branch
        ]);
    }



    /**
     * Update branch
     */
    public function saveEdit(Branch $branch, Request $request)
    {
        $this->authorize("update", $branch);

        $data = (object)$request->validate([
            "name" => "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "email" => "required|email",
            "contact_number" => "required|numeric|digits:10",
            "parking_slots" => "required|integer|min:1",
            "manager_id" => [
                "nullable", "integer",
                Rule::exists('users', "id")->where(function ($query) {
                    return $query->where('role', User::$ROLE_MANAGER);
                }),
                Rule::unique("branches", "manager_id")->ignore($branch->id, "id"),
            ]
        ], [
            "manager_id.exists" => "The selected manager is not a valid manager or the account does not exist.",
            "manager_id.unique" => "Selected manager is already associated with another branch.",
            "manager_id.*" => "The manager field is invalid.",
        ], [
            "manager_id" => "manager"
        ]);


        $branch->name = $data->name;
        $branch->address_line_1 = $data->address_line_1;
        $branch->address_line_2 = $data->address_line_2;
        $branch->address_line_3 = $data->address_line_3;
        $branch->address_city_id = $data->address_city;
        $branch->email = $data->email;
        $branch->contact_number = $data->contact_number;
        $branch->parking_slots = $data->parking_slots;
        $branch->manager_id = $data->manager_id;
        $branch->save();

        return redirect()->route('branches-management.edit', ['branch' => $branch->id])
            ->with(["branch-update-or-create-success-message" => "Branch has been updated."]);
    }


    /**
     * Show branch details
     */
    public function showBranch(Branch $branch, Request $request)
    {
        return view("admin.branch-management.view", [
            "branch" => $branch
        ]);
    }


    public function showAdminAccounts(Branch $branch, Request $request)
    {
        $this->authorize("viewAny", User::class);

        $data = (object)$request->validate([
            "email" => "nullable|email"
        ]);

        $adminAccounts = User::query()
            ->where(function (Builder $query) use ($branch) {
                $query
                    ->where("work_for_branch_id", $branch->id)
                    ->orWhereHas("ManageBranch", function (Builder $query) use ($branch) {
                        $query->where("id", $branch->id);
                    });
            })
            ->whereIn("role", [User::$ROLE_MANAGER, User::$ROLE_COUNTER]);

        if (isset($data->email)) {
            $adminAccounts->where("email", $data->email);
        }

        $adminAccounts = $adminAccounts->orderBy("role", "desc")->paginate(20);

        return view("admin.branch-management.admin-management.index", [
            "admin_accounts" => $adminAccounts,
            "branch" => $branch
        ]);
    }
    /**
     * Show new admin profile page
     */
    public function showNewAdmin(Branch $branch, Request $request)
    {
        $districts = SriLankaDistrict::all();
        $randomPassword = Str::random(8);

        if (Auth::user()->isSuperAdminAccount()) {
            $roles = [
                User::$ROLE_MANAGER => "Manager",
                User::$ROLE_COUNTER => "Counter",
            ];
        } else {
            $roles = [
                User::$ROLE_COUNTER => "Counter",
            ];
        }
        return view("admin.branch-management.admin-management.new-admin", [
            "districts" => $districts,
            "random_password" => $randomPassword,
            "roles" => $roles,
            "branch" => $branch
        ]);
    }
    /**
     * Save new admin profile
     */
    public function saveNewAdmin(Branch $branch, Request $request)
    {
        $this->authorize("create", User::class);

        $data = (object)$request->validate([
            "email" => "required|email|unique:users,email",
            "password" => "required|string|min:8",
            "first_name" => "required|string",
            "last_name" =>  "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "contact_number" => "required|numeric|digits:10",
            "role" => "required|string|in:" . User::$ROLE_COUNTER,
        ], [
            "branch_id.exists" => "The selected branch is not a valid branch or it has already associated with another manager.",
            "branch_id.*" => "The branch field is invalid.",
        ]);

        $admin = new User();
        $admin->email = $data->email;
        $admin->first_name = $data->first_name;
        $admin->last_name = $data->last_name;
        $admin->address_line_1 = $data->address_line_1;
        $admin->address_line_2 = $data->address_line_2;
        $admin->address_line_3 = $data->address_line_3;
        $admin->address_city_id = $data->address_city;
        $admin->contact_number = $data->contact_number;
        $admin->role = $data->role;
        $admin->password = Hash::make($data->password);
        $admin->work_for_branch_id = $branch->id;
        $admin->save();

        return redirect()->route('branches-management.admin-management.new', ['branch' => $branch->id])
            ->with(["profile-create-success-message" => "New admin account is successfully created."]);
    }

    /**
     * Show admin profile
     */
    public function showAdmin(Branch $branch, User $admin, Request $request)
    {
        $this->authorize("view", $admin);

        return view("admin.branch-management.admin-management.view-admin", [
            "branch" => $branch,
            "admin" => $admin,
        ]);
    }

    /**
     * Show admin profile
     */
    public function editAdmin(Branch $branch, User $admin, Request $request)
    {
        $this->authorize("view", $admin);

        $roles = [
            User::$ROLE_MANAGER => "Manager",
            User::$ROLE_COUNTER => "Counter",
        ];

        $districts = SriLankaDistrict::all();
        return view("admin.branch-management.admin-management.edit-admin", [
            "branch" => $branch,
            "admin" => $admin,
            "districts" => $districts,
            "roles" => $roles
        ]);
    }
    /**
     * Show admin profile
     */
    public function saveEditAdmin(Branch $branch, User $admin, Request $request)
    {
        $this->authorize("update", $admin);

        $data = (object)$request->validate([
            "first_name" => "required|string",
            "last_name" =>  "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "contact_number" => "required|numeric|digits:10",
            "role" => [
                Rule::when(function () {
                    return Auth::user()->isSuperAdminAccount();;
                }, [
                    "required", "numeric", Rule::in([User::$ROLE_MANAGER, User::$ROLE_COUNTER]),
                ])
            ],
            "is_activated" => [
                Rule::when(function () use ($admin) {
                    return (Auth::user()->isSuperAdminAccount() || Auth::user()->isManagerAccount())
                        &&  Auth::user()->id !== $admin->id;
                }, [
                    "required", "numeric", "in:1,0"
                ])
            ]
        ]);


        $admin->first_name = $data->first_name;
        $admin->last_name = $data->last_name;
        $admin->address_line_1 = $data->address_line_1;
        $admin->address_line_2 = $data->address_line_2;
        $admin->address_line_3 = $data->address_line_3;
        $admin->address_city_id = $data->address_city;
        $admin->contact_number = $data->contact_number;

        if ((Auth::user()->isSuperAdminAccount() || Auth::user()->isManagerAccount())
            &&  Auth::user()->id !== $admin->id
        ) {
            $admin->is_activated = $data->is_activated;
        }

        if (Auth::user()->isSuperAdminAccount()) {
            $admin->role = $data->role;

            if (isset($admin->ManageBranch)) {
                Branch::findorfail($admin->ManageBranch->id)->Manager()->disassociate($admin)->save();
            }
            if (isset($admin->WorkForBranch)) {
                $admin->WorkForBranch()->disassociate($data->branch_id)->save();
            }

            if ($data->role == User::$ROLE_MANAGER) {
                if (isset($data->branch_id)) {
                    Branch::findorfail($data->branch_id)->Manager()->associate($admin)->save();
                }
            } else  if ($data->role == User::$ROLE_COUNTER) {
                if (isset($data->branch_id)) {
                    $admin->WorkForBranch()->associate($data->branch_id)->save();
                }
            }
        }

        $admin->save();


        return redirect()->route("branches-management.admin-management.edit", ['branch' => $branch->id, 'admin' => $admin->id])
            ->with(["profile-update-success-message" => "Admin account information is successfully updated."]);
    }
}
