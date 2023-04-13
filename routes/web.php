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
    } else if (Auth::user()->isManagerAccount()) {
        if (isset(Auth::user()->ManageBranch->id)) {
            return redirect()->route("branches-management.view", ["branch" => Auth::user()->ManageBranch->id]);
        } else {
            return view("admin.errors.no-branch");
        }
    } else if (Auth::user()->isCounterAccount()) {
        if (isset(Auth::user()->WorkForBranch->id)) {
            return redirect()->route("branches-management.view", ["branch" => Auth::user()->WorkForBranch->id]);
        } else {
            return view("admin.errors.no-branch");
        }
    } else if (Auth::user()->isUserAccount()) {
        return redirect()->route("find-parking-lot");
    } else {
        abort(404);
    }
})->middleware("auth")->name("home");

// Authentication routes
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


// Super admin account's routes
Route::group(["middleware" => "auth.role:super-admin", "prefix" => "s-admin"], function () {
    Route::get("dashboard", "SuperAdmin\DashboardController@index")->name("super-admin.dashboard");

    Route::get("branches-management", "Admin\BranchManegementController@index")->name("branches-management");
    Route::get("branches-management/new", "Admin\BranchManegementController@showNew")->name("branches-management.new");
    Route::post("branches-management/new", "Admin\BranchManegementController@saveNew");
    Route::get("branches-management/search-managers", "Admin\BranchManegementController@searchManagers")->name("branches-management.search-managers");
});

// Super Admin, Manager, Counter account's routes (common)
Route::group(["middleware" => ["auth.role:super-admin,manager,counter", "auth.admin.has.branch"]], function () {



    Route::get("branches-management/{branch}/edit", "Admin\BranchManegementController@showEdit")->name("branches-management.edit");
    Route::post("branches-management/{branch}/edit", "Admin\BranchManegementController@saveEdit");
    Route::get("branches-management/{branch}", "Admin\BranchManegementController@showBranch")->name("branches-management.view");
    Route::get("branches-management/{branch}/view", "Admin\BranchManegementController@showExtraView")->name("branches-management.view-extra");
    Route::get("branches-management/{branch}/admin-management", "Admin\BranchManegementController@showAdminAccounts")->name("branches-management.admin-management");


    Route::get("branches-management/{branch}/admin-management/new", "Admin\BranchManegementController@showNewAdmin")->name("branches-management.admin-management.new");
    Route::post("branches-management/{branch}/admin-management/new", "Admin\BranchManegementController@saveNewAdmin");

    Route::get("branches-management/{branch}/admin-management/{admin}/edit", "Admin\BranchManegementController@editAdmin")->name("branches-management.admin-management.edit");
    Route::post("branches-management/{branch}/admin-management/{admin}/edit", "Admin\BranchManegementController@saveEditAdmin");

    Route::get("branches-management/{branch}/admin-management/{admin}", "Admin\BranchManegementController@showAdmin")->name("branches-management.admin-management.view");


    Route::get("bookings-management", "Admin\BookingsManagementController@index")->name("bookings-management");
    Route::get("bookings-management/{booking:reference_id}", "Admin\BookingsManagementController@view")->name("bookings-management.view");
    Route::post("bookings-management/{booking:reference_id}/mark-as-ongoing", "Admin\BookingsManagementController@markAsOnGoing")->name("bookings-management.mark-as-ongoing");
    Route::post("bookings-management/{booking:reference_id}/mark-as-cancelled", "Admin\BookingsManagementController@markAsCancelled")->name("bookings-management.mark-as-cancelled");
    Route::post("bookings-management/{booking:reference_id}/mark-as-finished", "Admin\BookingsManagementController@markAsFinished")->name("bookings-management.mark-as-finished");



    Route::get("admin-management", "Admin\AdminManagementController@index")->name("super-admin.admin-management");
    Route::get("admin-management/new", "Admin\AdminManagementController@showNew")->name("super-admin.admin-management.new");
    Route::post("admin-management/new", "Admin\AdminManagementController@saveNew");
    Route::get("admin-management/search-branches", "Admin\AdminManagementController@searchBranches")->name("super-admin.admin-management.search-brancges");

    Route::group(["middleware" => "can:update,admin"], function () {
        Route::get("admin-management/{admin}/edit", "Admin\AdminManagementController@showEdit")->name("super-admin.admin-management.edit");
        Route::post("admin-management/{admin}/edit", "Admin\AdminManagementController@saveEdit");
    });
    Route::group(["middleware" => "can:view,admin"], function () {
        Route::get("admin-management/{admin}", "Admin\AdminManagementController@showAdmin")->name("super-admin.admin-management.view");
    });
});


// User account's routes
Route::group(["middleware" => "auth.role:user"], function () {
    Route::get("/find-parking-lot", "User\BranchesController@index")->name("find-parking-lot");

    Route::get("/my-bookings", "User\BookingsController@index")->name("my-bookings");
    Route::get("/my-bookings/place-booking", "User\BookingsController@new")->name("my-bookings.new");
    Route::post("/my-bookings/place-booking", "User\BookingsController@placeBooking");

    Route::get("/my-bookings/{booking:reference_id}", "User\BookingsController@view")->name("my-bookings.view");
    Route::post("/my-bookings/{booking:reference_id}/mark-as-cancelled", "User\BookingsController@markAsCancelled")->name("my-bookings.cancel");

    Route::get("balance-and-recharge/transactions", "User\TransactionsController@allTransactions")->name("balance-and-recharge.transactions");
    Route::get("balance-and-recharge/holds", "User\TransactionsController@allHolds")->name("balance-and-recharge.holds");

    Route::get("balance-and-recharge/recharge-account", "User\TransactionsController@showRechargeForm")->name("balance-and-recharge.recharge");
    Route::post("balance-and-recharge/recharge-account/confirm", "User\TransactionsController@confirmRecharge")->name("balance-and-recharge.recharge.confirm");

    Route::get("balance-and-recharge/payment-status", "User\TransactionsController@paymentStatusPage")->name("balance-and-recharge.recharge.status");
});

// common routes for all users
Route::group(["middleware" => ["auth"]], function () {
    Route::get("account-management", "AccountManagementController@index")->name("account-management");
    Route::post("account-management", "AccountManagementController@updateProfile")->name("account-management.update");
    Route::post("account-management/change-password", "AccountManagementController@changePassword")->name("account-management.change-password");
});



Route::post("payment-listener", "User\TransactionsController@payherePaymentWebhook")->name("payment-listener");

Route::fallback(function () {
    abort(404);
});
