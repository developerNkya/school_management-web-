<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperController extends Controller
{
    public function classExistance(Request $request)
    {    
    $classExists = SchoolClass::where('school_id',Auth::user()->school_id)->first();
    if (!$classExists == null) {
        return response()->json(['exists' => true]);
    }else{
        return response()->json(['exists' =>false]);
    }
    }
}
