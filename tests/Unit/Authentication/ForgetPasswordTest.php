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
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;

class ResetsPasswordTest extends TestCase
{
    use RefreshDatabase;

    /**
     * test step 1 of the forgot password flow (show email request form)
     *
     * @return void
     */
    public function testShowForgotPasswordStep1()
    {
        // send request to forgot password flow's first endpoint
        $response = $this->get(route("auth.forgot-password.step.1"));

        // it should render the related vuew
        $response->assertStatus(200);
    }

    /**
     * test email verification of step 1 for a valid and existing user
     *
     * @return void
     */
    public function testStep1SuccessOnValidEmailAddress()
    {
        // create a test user and insert it to the database.
        $user = User::factory()->role(User::$ROLE_USER)->create();

        // clear all the previous tokens if exists
        PasswordResetToken::query()->delete();

        // inject the user's email into forgot password endpoint which need to be tested.
        $response = $this->post(route("auth.forgot-password.step.1"), [
            "email" => $user->email
        ]);

        // check whether it create a reset token.
        $tokenCreated = PasswordResetToken::query()->where("email", $user->email)->count() > 0;

        // we expect that the token is created
        $this->assertTrue($tokenCreated);

        // we expect that after end if the flow it shall redirected to the login page.
        $response->assertStatus(302);
    }


    /**
     * test email verification of step 1 for invalid user
     *
     * @return void
     */
    public function testStep1FailOnInValidEmailAddress()
    {
        // create a uniqe email address
        $email = time() . "notexist@mail.com";

        // remove all existing reset tokens.
        PasswordResetToken::query()->delete();

        // inject the email address into the endpoint
        $response = $this->post(route("auth.forgot-password.step.1"), [
            "email" => $email
        ]);

        // check whether it created a token
        $tokenCreated = PasswordResetToken::query()->where("email", $email)->count() > 0;

        // we expect that the token is not created because the releted user is not in our system.
        // and nether it should show an error

        $response->assertSessionHasNoErrors();
        $this->assertFalse($tokenCreated);

        // after end of the task it should redirect to the login page
        $response->assertStatus(302);
    }

    /**
     * Test Step 2 of the Forgot password flow (new password form) for
     * a valid reset link
     *
     * @return void
     */
    public function testShowPasswordResetFormStep2()
    {
        // create a test user
        $user = User::factory()->role(User::$ROLE_USER)->create();

        // create a test password reset token
        $token = Str::random(10) . $user->id . time() . random_int(10, time());
        PasswordResetToken::create([
            "email" => $user->email,
            "token" => Hash::make($token)
        ]);

        // inject data into the endpoint
        $response = $this->get(route("auth.forgot-password.step.2"), [
            "email" => $user->email,
            "token" => $token
        ]);


        // there should't have any errors
        $response->assertSessionDoesntHaveErrors([
            "email", "token"
        ]);

        // after end of the task it should redirect into the login page
        $response->assertStatus(302);
    }


    /**
     * Test Step 2 of the Forgot password flow (show new password form) for
     * an invalid reset link
     *
     * @return void
     */
    public function testShowInvalidPasswordResetLinkStep2()
    {
        $user = User::factory()->role(User::$ROLE_USER)->create();

        // generate an invalid reset token which will not going to insert into the database.
        $token = Str::random(10) . $user->id . time() . random_int(10, time());

        // inject data into the endpoint
        $response = $this->get(route("auth.forgot-password.step.2"), [
            "email" => $user->email,
            "token" => $token
        ]);

        // it should return an error
        $response->assertSessionHasErrors([
            "error-message"
        ]);

        // after end of the task it should redirect to the login page
        $response->assertStatus(302);
    }

    /**
     * Test Step 2 of the Forgot password flow (validate create new password form)
     * for a valid reset link
     *
     * @return void
     */
    public function testShowCreatePasswordFormForAValidResetLink()
    {
        // create test user
        $user = User::factory()->role(User::$ROLE_USER)->create();

        // create a random password
        $newPassword = Str::random(10);

        // create a test reset token
        $token = Str::random(10) . $user->id . time() . random_int(10, time());
        PasswordResetToken::create([
            "email" => $user->email,
            "token" => Hash::make($token),
        ]);

        // inject data into the endpoint
        $response = $this->post(route("auth.forgot-password.step.2"), [
            "email" => $user->email,
            "token" => $token,
            "password" => $newPassword,
            "password_confirmation" => $newPassword
        ]);

        // it should replace the old password with the given new password
        $this->assertTrue(Hash::check($newPassword, $user->fresh()->password));

        // a successfull attempt should not return any errors
        $response->assertSessionDoesntHaveErrors([
            "email", "token", "password", "error-message"
        ]);


        // after end of the task it should redirect into the login page
        $response->assertStatus(302);
    }

    /**
     * Test Step 2 of the Forgot password flow (validate create new password form)
     * for an invalid reset link
     *
     * @return void
     */
    public function testShowCreatePasswordFormForAnInValidResetLink()
    {
        // create test user
        $user = User::factory()->role(User::$ROLE_USER)->create();

        // create a random password
        $newPassword = Str::random(10);

        // create a test reset token which will not inserted to the database
        $token = Str::random(10) . $user->id . time() . random_int(10, time());


        // inject data into the endpoint
        $response = $this->post(route("auth.forgot-password.step.2"), [
            "email" => $user->email,
            "token" => $token,
            "password" => $newPassword,
            "password_confirmation" => $newPassword
        ]);

        // it should not replace the old password with the given new password
        $this->assertFalse(Hash::check($newPassword, $user->fresh()->password));

        // a faild attempt should return an error
        $response->assertSessionHasErrors([
            "error-message"
        ]);

        // after end of the task it should redirect into the login page
        $response->assertStatus(302);
    }
}
