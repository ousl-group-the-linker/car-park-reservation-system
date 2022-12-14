<?php


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    if (Auth::user()->isSuperAdminAccount()) {
        return redirect()->route("super-admin.dashboard");
    }
})->middleware("auth")->name("home");


Route::group([], function () {
    Route::group(["middleware" => ["guest"]], function () {
        Route::get('/login', "Auth\AuthController@showLogin")->name("auth.login");
        Route::post('/login', "Auth\AuthController@login");

        Route::get('/register', "Auth\AuthController@showRegister")->name("auth.register");
        Route::post('/register', "Auth\AuthController@register");

        Route::get('/forgot-password/step-1', "Auth\AuthController@showForgotPasswordStep1")->name("auth.forgot-password.step.1");
        Route::post('/forgot-password/step-1', "Auth\AuthController@validateForgotPasswordStep1");

        Route::get('/forgot-password/step-2', "Auth\AuthController@showForgotPasswordStep2")->name("auth.forgot-password.step.2");
        Route::post('/forgot-password/step-2', "Auth\AuthController@uodatePasswordForgotPasswordStep2");
    });
    Route::post('/logout', "Auth\AuthController@logout")->middleware("auth")->name("auth.logout");
});




Route::group(["middleware" => "auth"], function () {
    Route::get("s-admin/dashboard", "SuperAdmin\DashboardController@index")->name("super-admin.dashboard");

    Route::get("s-admin/branches-management", "SuperAdmin\BranchManegementController@index")->name("super-admin.branches-management");

    Route::get("s-admin/bookings-management", "SuperAdmin\BookingsManagementController@index")->name("super-admin.bookings-management");

    Route::get("s-admin/transactions-management", "SuperAdmin\TransactionsManagementController@index")->name("super-admin.transactions-management");

    Route::get("s-admin/admin-management", "SuperAdmin\AdminManagementController@index")->name("super-admin.admin-management");

    Route::get("account-management", "AccountManagementController@index")->name("account-management");
    Route::post("account-management", "AccountManagementController@updateProfile")->name("account-management.update");
    Route::post("account-management/change-password", "AccountManagementController@changePassword")->name("account-management.change-password");
});
