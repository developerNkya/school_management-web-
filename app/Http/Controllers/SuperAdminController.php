<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        return view('super_admin.index');
    }

    public function addSchool(Request $request)
    {
        // return view('super_admin.index');
    }
}
