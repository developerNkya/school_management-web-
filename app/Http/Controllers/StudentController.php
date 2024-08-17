<?php

namespace App\Http\Controllers;


use App\Models\StudentInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return view('student.index');
    }
    public function aboutMe(Request $request)
    {
        $student_info = StudentInfo::with('SchoolClass','section')->where('user_id',Auth::user()->id)
                        ->first();

     
        return view('student.about_me', ['student_info' => $student_info]);
    }
}
