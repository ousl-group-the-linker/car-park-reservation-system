<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $usersCount = User::where("role", User::$ROLE_USER)->count();
        $branchesCount = Branch::count();
        $totalCapacity = Branch::sum("parking_slots");

        $pendingBookingsCount =Booking::where("status", Booking::STATUS_PENDING)->count();
        $ongoingBookingsCount =Booking::where("status", Booking::STATUS_ONGOING)->count();
        $finishedBookingsCount =Booking::where("status", Booking::STATUS_FINISHED)->count();


        return view("super-admin.dashboard.index", [
            "users_count" => $usersCount,
            "branches_count" => $branchesCount,
            "total_parking_slots" => $totalCapacity,
            "pending_bookings_count" => $pendingBookingsCount,
            "ongoing_bookings_count" => $ongoingBookingsCount,
            "finished_bookings_count" => $finishedBookingsCount,
        ]);
    }
}
