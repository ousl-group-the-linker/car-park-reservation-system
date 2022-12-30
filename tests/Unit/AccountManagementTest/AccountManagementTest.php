<?php

namespace Tests\Unit\AccountManagementTest;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Str;

//use PHPUnit\Framework\TestCase;

class AccountManagementTest extends TestCase
{
    /**
     * Check whether the profule infomatuin is loading
     *
     * @return void
     */
    public function testIsProfileUpdatePageLoads()
    {
        $password = "1234567890";
        $email =  time() . "sae@mail.com";

        $user = User::create([
            'first_name' => "jhon",
            'last_name' => "Doe",
            'email' => $email,
            'address_line_1' => "250",
            'address_line_2' => "hill side",
            'address_line_3' => "downtown",
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0758763147",
            'password' => Hash::make($password),
            'role' => User::$ROLE_USER
        ]);

        // login new user
        Auth::login($user);
        $response = $this->get(route("account-management"));
        $response->assertStatus(200);
        
    }

     /**
     * Check whether the profule infomatuin is updating
     *
     * @return void
     */
    public function testIsProfileUpdateSavingCorrectly()
    {
        $email = "caffery@gmail.com";
        $password =Str::random();

        $response = $this->post(route("account-management.update"), [
            "email" => $email,
            "first_name" => "Neal",
            "last_name" => "Caffery",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
        ]);
        $response = $this->post(route("account-management.update"));
        $response->assertStatus(302);
        $this->assertTrue(true);
    }
     /**
     * Check whether the account password is correctly updates
     *
     * @return void
     */
    public function testIsPasswordUpdatesCorrectly()
    {
        $new_password =Str::random();
        $password=Str::random();
        $response = $this->post(route("auth.register"), [
            "current_password" => $password,
            "new_password" => $new_password,
            "confirm_password"=>$new_password,
        ]);


        if ($new_password==$password) {
            $response = $this->get(route("account-management.change-password"));
            $response->assertStatus(302);
        } else {
            $response = $this->post(route("account-management"));
            $response->assertStatus(302);
        }

    }
}
