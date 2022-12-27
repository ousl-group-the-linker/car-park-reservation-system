<?php

namespace Tests\Unit\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SriLankaCity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LogOutTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsLogoutWorking()
    {
        // create new user
        $password = "1234567890";
        $email =  time() . "sarae@mail.com";

        $user = User::create([
            'first_name' => "Sarah",
            'last_name' => "Ellis",
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

        $response = $this->post(route("auth.logout"));

        // check whether the response is a redirect
        $response->assertStatus(302);

        // check whether the user is actually logged out
        $this->assertFalse(Auth::check());
    }
}
