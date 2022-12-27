<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\ResetAccountPassword;
use App\Models\PasswordResetToken;
use App\Models\SriLankaDistrict;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use  Illuminate\Support\Str;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin(Request $request)
    {
        return view("auth.login");
    }
    /**
     * Attempt to login
     */
    public function login(Request $request)
    {
        $data = (object)$request->validate([
            "email" => "required|email",
            "password" =>  "required|string",
            "remember_me" => ""
        ]);

        // check whether the given credentials are correct.
        if (Auth::attempt(['email' => $data->email, 'password' => $data->password], boolval($data->remember_me ?? false))) {
            if (!Auth::user()->is_activated) {
                return redirect()->route("auth.login")
                    ->withErrors(["email" => "Your account is deactivated, please contact the system administrator."])
                    ->withInput($request->only("email"));
            }

            return redirect()->route("home");
        }

        return redirect()->route("auth.login")
            ->withErrors(["email" => "Email address or password is invalid."])
            ->withInput($request->only("email"));
    }

    /**
     * Logout the currently authenticated user
     */
    public function logout(Request $request)
    {
        Auth::logoutCurrentDevice();

        return redirect()->route("auth.login");
    }





    /**
     * Show register/signup form
     */
    public function showRegister(Request $request)
    {
        $districts = SriLankaDistrict::all();

        return view("auth.register", [
            "districts" => $districts
        ]);
    }

    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        $data = (object)$request->validate([
            "email" => "required|email|unique:users,email",
            "first_name" => "required|string",
            "last_name" =>  "required|string",
            "address_line_1" =>  "required|string",
            "address_line_2" =>  "nullable|string",
            "address_line_3" => "nullable|string",
            "address_city" => "required|exists:sri_lanka_cities,id",
            "contact_number" => "required|numeric|digits:10",
            "password" =>  "required|string|min:8|confirmed",
            "i_aggree" => "accepted"
        ], [], [
            "i_aggree" => "terms and conditions"

        ]);

        $user = User::create([
            "email" => $data->email,
            "first_name" => $data->first_name,
            "last_name" => $data->last_name,
            "address_line_1" => $data->address_line_1,
            "address_line_2" => $data->address_line_2,
            "address_line_3" => $data->address_line_3,
            "address_city_id" => $data->address_city,
            "contact_number" => $data->contact_number,
            "password" => Hash::make($data->password),
            "role" => User::$ROLE_USER //the user is a client
        ]);

        // login the created user
        Auth::login($user);

        return redirect()->route("home");
    }






    /**
     * Forgot password - step 1
     * Show email requesting form
     */
    public function showForgotPasswordStep1(Request $request)
    {
        return view("auth.forgot-password-1");
    }
    /**
     * Forgot password - step 1
     * Send  password reset token to the requested email
     */
    public function validateForgotPasswordStep1(Request $request)
    {
        $data = (object)$request->validate([
            "email" => "required|email"
        ]);

        $user = User::where("email", $data->email)->first();

        // send the token to the given email address only if the account exists.
        if ($user) {
            $token = PasswordResetToken::create([
                "email" => $user->email,
                "token" => Hash::make(Str::random(10) . $user->id . time() . random_int(10, time()))
            ]);

            $message = (new ResetAccountPassword($token))
                ->onQueue(config("queue.queues.email_queue"));

            Mail::to($user)->queue($message);
        }

        return redirect()->route("auth.forgot-password.step.1")
            ->with(["success-message" => "Please check your inbox! If the account exists, you will receive instructions."]);
    }


    /**
     * Forgot password - step 2
     * Show password request form
     */
    public function showForgotPasswordStep2(Request $request)
    {
        $token = $this->validatePasswordResetToken($request->input("email"), $request->input("token"));

        return view("auth.forgot-password-2", [
            "email" => $token->token->email,
            "token" => $token->token->token
        ]);
    }

    /**
     * Forgot password - step 2
     * Update the password & Finish the process
     */
    public function uodatePasswordForgotPasswordStep2(Request $request)
    {
        $token = $this->validatePasswordResetToken($request->input("email"), $request->input("token"));

        $data = (object)$request->validate([
            "password" => "required|min:8|confirmed"
        ]);

        $user = $token->user;
        $user->password = Hash::make($data->password);
        $user->save();

        return redirect()->route("auth.login")
            ->with(["success-message" => "You account password has been reset."]);
    }

    /**
     * Validate the given token
     * @return object
     */
    private function validatePasswordResetToken($email, $token)
    {
        // validate token & email
        $validator = Validator::make(["email" => $email, "token" => $token], [
            "token" => "required|string|exists:password_reset_tokens,token",
            "email" => "required|email|exists:password_reset_tokens,email",
            "email" => "required|email|exists:users,email"
        ]);

        $validator->after(function ($validator) {
            if ($validator->errors()->count() > 0) return;

            $data = (object)$validator->getData();

            // check whether the requested token is valid
            $tokenCount = PasswordResetToken::where("email", $data->email)
                ->where("token", $data->token)
                ->where("created_at", ">", Carbon::now()->subHours(1))
                ->count();


            if ($tokenCount < 1) {
                $validator->errors()->add("error", "Invalid password reset link.");
            }
        });

        if ($validator->fails()) {
            return redirect()->route("auth.forgot-password.step.1")
                ->withErrors(["error-message" => "Invalid password reset link"]);
        }

        $data = (object)$validator->validated();

        $token = PasswordResetToken::where("email", $data->email)
            ->where("token", $data->token)
            ->where("created_at", ">", Carbon::now()->subHours(1))
            ->first();

        $user = User::where("email", $data->email)->first();

        return (object)["user" => $user, "token" => $token];
    }
}
