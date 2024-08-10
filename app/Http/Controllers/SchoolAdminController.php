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
        $classes = SchoolClass::get();
        return view('school_admin.class_page',['classes'=>$classes]);
    }

    public function studentsPage(Request $request)
    {
        $classes = SchoolClass::get();
        return view('school_admin.students_page',['classes'=>$classes]);
    }

    
    public function addClass(Request $request) {

        try{
        $rules = [
            'class_name' => 'required',
        ];

        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return redirect()->route('add_class_page')->withErrors(['error' => $error]);
        }


        $class = new SchoolClass();
        $class->name = $request->class_name;
        $class->save();

        return redirect()->route('add_class_page')->with('message', 'Class added successfully!');
    } catch (\Exception $e) {
        return redirect()->route('add_class_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
}


public function addStudent(Request $request) {


    return redirect()->route('add_student_page')->with('message', 'Student added successfully!');
    try{
    $rules = [
        'class_name' => 'required',
    ];

    $validator = validator::make($request->all(), $rules);
    $error = $validator->errors()->first();
    if ($validator->fails()) {
        return redirect()->route('add_class_page')->withErrors(['error' => $error]);
    }


    $class = new SchoolClass();
    $class->name = $request->class_name;
    $class->save();

    return redirect()->route('add_class_page')->with('message', 'Class added successfully!');
} catch (\Exception $e) {
    return redirect()->route('add_class_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
}
}
}
