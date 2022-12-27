<?php

namespace tests\Unit\Authentication;

use App\Models\User;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use  Illuminate\Support\Str;
use App\Mail\ResetAccountPassword;
use App\Models\PasswordResetToken;



class ResetsPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Sends the password reset email when the user exists.
     *
     * @return void
     */
    public function testSendsPasswordResetEmail()
    {
        $user =User::factory()->role(User::$ROLE_USER)->create();


        $response = $this->post(route("auth.forgot-password.step.1"));

        $response->assertStatus(302);
    }

    /**
     * Does not send a password reset email when the user does not exist.
     *
     * @return void
     */
    public function testDoesNotSendPasswordResetEmail()
    {
        $this->doesntExpectJobs(ResetPassword::class);

        $this->get(route("auth.forgot-password.step.1"));
    }

    /**
     * Displays the form to reset a password.
     *
     * @return void
     */
    public function testDisplaysPasswordResetForm()
    {
        $response = $this->get(route("auth.forgot-password.step.2"));

        $response->assertStatus(302);
    }

    /**
     * Allows a user to reset their password.
     *
     * @return void
     */
    public function testChangesAUsersPassword()
    {
        $password = "1234567890@#";
        $user = User::factory()->role(User::$ROLE_USER)->create();


        //$token = Hash::make(Str::random(10) . $user->id . time() . random_int(10, time()));
        $token = PasswordResetToken::create([
            "email" => $user->email,
            "token" => Hash::make(Str::random(10) . $user->id . time() . random_int(10, time()))
        ]);

        $message = (new ResetAccountPassword($token));

        

        $response = $this->post(route("auth.forgot-password.step.2"), [
            'token' => $token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password'
        ]);

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}