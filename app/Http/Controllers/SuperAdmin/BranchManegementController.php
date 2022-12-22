<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

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

        return view("super-admin.branch-management.index", [
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


        return view("super-admin.branch-management.new", [
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
        ]);

        return redirect()->route('super-admin.branches-management.new')
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
    public function showEdit(Branch $branch, Request $request)
    {
        $districts = SriLankaDistrict::all();

        return view("super-admin.branch-management.edit", [
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

        return redirect()->route('super-admin.branches-management.edit', ['branch' => $branch->id])
            ->with(["branch-update-or-create-success-message" => "Branch has been updated."]);
    }


    /**
     * Show branch details
     */
    public function showAdmin(User $admin, Request $request)
    {
        $this->authorize("view", $admin);

        return view("super-admin.admin-management.view", [
            "admin" => $admin
        ]);
    }
}
