<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testisLoginWorkingForAValidUserTest()
    {
        $response = $this->post(route("auth.login"), [
            "email" => "sdsdsd@gmail.com",
            "password" => "Sdsdsdsd"
        ]);

        $response->assertStatus(302);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIsLogoutWorking()
    {
        $response = $this->post(route("auth.login"), [
            "email" => "sdsdsd@gmail.com",
            "password" => "Sdsdsdsd"
        ]);

        $response->assertStatus(302);
    }
}
