<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SriLankaCity;
use App\Models\SriLankaDistrict;
use CitiesSriLanka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AccountManagementController extends Controller
{

    /**
     * Show profile management form.
     */
    public function index(Request $request)
    {
        $districts = SriLankaDistrict::all();

        return view("account-management.index", [
            "districts" => $districts
        ]);
    }

    /**
     * Update profile data
     */
    public function updateProfile(Request $request)
    {

        $data = (object)$request->validateWithBag("profileUpdate", [
            "first_name" => "required|string",
            "last_name" =>  "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "contact_number" => "required|numeric|digits:10"
        ]);

        $user = Auth::user();
        $user->first_name = $data->first_name;
        $user->last_name = $data->last_name;
        $user->address_line_1 = $data->address_line_1;
        $user->address_line_2 = $data->address_line_2;
        $user->address_line_3 = $data->address_line_3;
        $user->address_city_id = $data->address_city;
        $user->contact_number = $data->contact_number;
        $user->save();

        $route = route("account-management");
        return redirect()->to("{$route}#update-profile")
            ->with(["profile-update-success-message" => "Your account information is successfully updated."]);
    }

    /**
     * Change account password of currently logged user.
     */
    public function changePassword(Request $request)
    {
        $data = (object)$request->validateWithBag("changePasswordBag", [
            "current_password" => "bail|required|string",
            "new_password" =>  "required|string|min:8|confirmed"
        ]);


        $user = Auth::user();

        if (!Hash::check($data->current_password, $user->password)) {
            return redirect()->route("account-management")
                ->withErrors(["current_password" => "Invalid password."], "changePasswordBag");
        } else {
            $user->password = Hash::make($data->new_password);
            $user->save();

            $route = route("account-management");
            return redirect()->to("{$route}#change-password")
                ->with(["change-password-success-message" => "Your account password is successfully updated."], "changePasswordBag");
        }
    }
}
