<?php

namespace App\Http\Controllers;
use App\Models\SchoolClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class SchoolAdminController extends Controller
{
    public function index(Request $request)
    {
        return view('school_admin.index');
    }

    public function classPage(Request $request)
    {
        return view('school_admin.class_page');
    }

    
    public function addClass(Request $request)
    {
        $rules = [
            'class_name' => 'required',
        ];

        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error
            ]);
        }


        $class = new SchoolClass();
        $class->name = $request->class_name;
        $class->save();


    }
}
