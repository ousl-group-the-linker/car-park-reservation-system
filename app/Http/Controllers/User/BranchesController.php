<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\SriLankaDistrict;
use Illuminate\Http\Request;


class BranchesController extends Controller
{
    public function index(Request $request)
    {
        $districts = SriLankaDistrict::all();
        $branches = Branch::paginate(15);


        return view("user.branches.index", [
            "districts" => $districts,
            "branches" => $branches
        ]);
    }
}
