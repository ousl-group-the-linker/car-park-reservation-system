<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchManegementController extends Controller
{
    public function index(Request $request)
    {
        return view("super-admin.branch-management.index");
    }
}
