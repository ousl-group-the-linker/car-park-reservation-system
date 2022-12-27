<?php

namespace tests\Unit\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class LogInTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsLoginWorkingForAValidUserTest()
    {
        // create new user
        $password = "1234567890@#";

        $user=User::create([
            'first_name' => "Peter",
            'last_name' => "Burke",
            'email' => "peter@gmail.com",
            'address_line_1' => "500",
            'address_line_2'=> "high level",
            'address_line_3'=> "",
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0775896321",
            'password' => Hash::make($password),
            'role' => User::$ROLE_USER
        ]);

        // try to login
        $response = $this->post(route("auth.login"), [
            "email" =>$user->email,
            "password" => $password
        ]);


        // check whether the user is successfully logged in
        $this->assertTrue(Auth::check());

        // check whether the response is a redirect.
        $response->assertStatus(302);
    }

    public function testIsLoginWorkingForAInvalidUserTest()
    {
        // create new user
        $password = "1234567890@#";

        $user=User::create([
            'first_name' => "Diana",
            'last_name' => "bloom",
            'email' => "bloommail.com",
            'address_line_1' => "400",
            'address_line_2'=> "low level",
            'address_line_3'=> "",
            'address_city_id' => SriLankaCity::all()->random()->id,
            'contact_number' => "0745696321",
            'password' => Hash::make($password),
            'role' => User::$ROLE_USER
        ]);

        // try to login
        $response = $this->post(route("auth.login"), [
            "email" =>$user->email,
            "password" => $password
        ]);


        // check whether the user is successfully logged in
        $this->assertFalse(Auth::check());

        // check whether the response is a redirect.
        $response->assertStatus(302);
    }

}