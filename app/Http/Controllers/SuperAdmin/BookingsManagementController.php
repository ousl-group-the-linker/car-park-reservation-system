<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingsManagementController extends Controller
{
    public function index(Request $request)
    {
        return view("super-admin.bookings-management.index");
    }
}
