<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", User::class);

        $data = (object)$request->validate([
            "email" => "nullable|email"
        ]);

        $adminAccounts = User::query();

        if (($data->email ?? NULL) != null) {
            $adminAccounts->where("email", $data->email);
        }

        $adminAccounts = $adminAccounts->whereIn("role", [User::$ROLE_SUPER_ADMIN, User::$ROLE_MANAGER, User::$ROLE_COUNTER])
            ->orderBy("created_at", "DESC")
            ->paginate(20);

        return view("super-admin.admin-management.index", [
            "admin_accounts" => $adminAccounts
        ]);
    }

    /**
     * Show new admin profile page
     */
    public function showNew(Request $request)
    {
        $districts = SriLankaDistrict::all();
        $randomPassword = Str::random(8);
        $roles = [
            User::$ROLE_SUPER_ADMIN => "Super Admin",
            User::$ROLE_MANAGER => "Manager",
            User::$ROLE_COUNTER => "Counter",
        ];
        return view("super-admin.admin-management.new", [
            "districts" => $districts,
            "random_password" => $randomPassword,
            "roles" => $roles
        ]);
    }
    /**
     * Save new admin profile
     */
    public function saveNew(Request $request)
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
            "role" => ["required", "numeric", Rule::in([User::$ROLE_SUPER_ADMIN, User::$ROLE_MANAGER, User::$ROLE_COUNTER])],
            "branch_id" => [
                "nullable",
                Rule::when(function () use ($request) {
                    return ($request->input("role") == User::$ROLE_MANAGER
                        || $request->input("role") == User::$ROLE_COUNTER)
                        && ($request->input("branch_id") != null);
                }, [
                    Rule::exists('branches', "id")->whereNull("manager_id")
                ]),

            ]
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
        $admin->save();

        if (
            $data->role == User::$ROLE_MANAGER
            || $data->role == User::$ROLE_COUNTER
        ) {
            if (isset($data->branch_id)) {
                Branch::findorfail($data->branch_id)->Manager()->associate($admin)->save();
            }
        }

        return redirect()->route('super-admin.admin-management.new')
            ->with(["profile-create-success-message" => "New admin account is successfully created."]);
    }

    /**
     * Show admin profile edit page
     */
    public function showEdit(User $admin, Request $request)
    {
        $districts = SriLankaDistrict::all();
        $roles = [
            User::$ROLE_SUPER_ADMIN => "Super Admin",
            User::$ROLE_MANAGER => "Manager",
            User::$ROLE_COUNTER => "Counter",
        ];
        return view("super-admin.admin-management.edit", [
            "admin" => $admin,
            "districts" => $districts,
            "roles" => $roles
        ]);
    }

    /**
     * Update admin profile
     */
    public function saveEdit(User $admin, Request $request)
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
            "role" => ["required", "numeric", Rule::in([User::$ROLE_SUPER_ADMIN, User::$ROLE_MANAGER, User::$ROLE_COUNTER])],
            "is_activated" => "required|numeric|in:1,0",
            "branch_id" => [
                "nullable",
                Rule::when(function () use ($request) {
                    return $request->input("role") == User::$ROLE_MANAGER
                        || $request->input("role") == User::$ROLE_COUNTER;
                }, [
                    Rule::exists('branches', "id")->where(function ($query) use ($request, $admin) {

                        if (($admin->Branch->id ?? null) != $request->input("branch_id")) {
                            $query->whereNull('manager_id');
                        }
                    }),
                ]),

            ]
        ], [
            "branch_id.exists" => "The selected branch is not a valid branch or it has already associated with another manager.",
            "branch_id.*" => "The branch field is invalid.",
        ]);


        $admin->first_name = $data->first_name;
        $admin->last_name = $data->last_name;
        $admin->address_line_1 = $data->address_line_1;
        $admin->address_line_2 = $data->address_line_2;
        $admin->address_line_3 = $data->address_line_3;
        $admin->address_city_id = $data->address_city;
        $admin->contact_number = $data->contact_number;
        $admin->role = $data->role;
        $admin->is_activated = $data->is_activated;
        $admin->save();

        if (isset($admin->Branch)) {
            Branch::findorfail($admin->Branch->id)->Manager()->disassociate($admin)->save();
        }

        if (
            $data->role == User::$ROLE_MANAGER
            || $data->role == User::$ROLE_COUNTER
        ) {
            if (isset($data->branch_id)) {
                Branch::findorfail($data->branch_id)->Manager()->associate($admin)->save();
            }
        }

        return redirect()->route('super-admin.admin-management.edit', ['admin' => $admin->id])
            ->with(["profile-update-success-message" => "Admin account information is successfully updated."]);
    }

    /**
     * Search managers (json)
     */
    public function searchBranches(Request $request)
    {
        if (strlen($request->input("name")) < 1 && strlen($request->input("email")) < 1) {
            return response()->json(new Collection());
        }


        $branches = Branch::query();

        if (strlen($request->input("name")) > 0) {
            $branches->where("name", "like", "%" . $request->input("name") . "%");
        }

        if (strlen($request->input("email")) > 0) {
            $branches->where("email", $request->input("email"));
        }


        $branches = $branches->limit(10)->get();
        $branches->load(["City", "Manager"]);


        return response()->json($branches);
    }


    /**
     * Show admin profile
     */
    public function showAdmin(User $admin, Request $request)
    {
        $this->authorize("view", $admin);

        return view("super-admin.admin-management.view", [
            "admin" => $admin
        ]);
    }
}
