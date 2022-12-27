<?php

namespace tests\Unit\Authentication;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\SriLankaCity;

use Illuminate\Support\Str;
class SignupTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testValidUserRegistration()
    {
        $email = "caffery@gmail.com";
        $password =Str::random();

        $response = $this->post(route("auth.register"), [
            "email" => $email,
            "first_name" => "Neal",
            "last_name" => "Caffery",
            "address_line_1" => "110",
            "address_line_2" => "nyork",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "password" => $password,
            "password_confirmation" => $password,
            "i_aggree" => "yes"
        ]);

        $response->assertStatus(302); //it should redirect

        //show all errors if there is any
        if(session('errors') != null){ 
            var_dump(session('errors'));
        }

        $this->assertNull(session('errors')); //test that there should't be any errors

        $this->assertTrue(User::where("email",$email)->count()>0); //inserted user should be in the database
    }

    public function testInvalidUserRegistration()
    {
        $email = "Nickgmail.com";
        $password =Str::random();

        $response = $this->post(route("auth.register"), [
            "email" => $email,
            "first_name" => "Nick",
            "last_name" => "Halden",
            "address_line_1" => "200",
            "address_line_2" => "brooklyn",
            "address_line_3" => "",
            "address_city" => SriLankaCity::all()->random()->id,
            "contact_number" => "0777536987",
            "password" => $password,
            "password_confirmation" => $password,
            "i_aggree" => "yes"
        ]);

        $response->assertStatus(302); //it should redirect


       // $this->assertNull(session('errors')); //test that there should't be any errors

        $this->assertFalse(User::where("email",$email)->count()>0); //inserted user should be in the database
    }

}

