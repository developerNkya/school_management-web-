<?php

namespace App\Http\Controllers;
use App\Models\SchoolClass;
use App\Models\StudentInfo;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SchoolAdminController extends Controller
{
    public function index(Request $request)
    {
        return view('school_admin.index');
    }

    public function classPage(Request $request)
    {
        $classes = SchoolClass::where('school_id',Auth::user()->school_id)->get();
        return view('school_admin.class_page',['classes'=>$classes]);
    }

    public function studentsPage(Request $request)
    {
        $student_info = StudentInfo::where('school_id',Auth::user()->school_id)
                        ->get();
        $classes = SchoolClass::where('school_id',Auth::user()->school_id)->get();
        return view('school_admin.students_page',['students'=>$student_info,'classes'=>$classes]);
    }

    public function addClass(Request $request) {
        try {
            $rules = [
                'class_name' => 'required',
                'sections.*' => 'nullable|string',
            ];
    
            $validator = validator::make($request->all(), $rules);
            $error = $validator->errors()->first();
            if ($validator->fails()) {
                return redirect()->route('add_class_page')->withErrors(['error' => $error]);
            }
    
            $class = new SchoolClass();
            $class->name = $request->class_name;
            $class->school_id = Auth::user()->school_id;
            $class->sections = json_encode($request->sections);
            $class->save();
    
            return redirect()->route('add_class_page')->with('message', 'Class added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('add_class_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
    

public function addStudent(Request $request)
{
    $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'registration_no' => 'required|string|unique:student_info|max:255',
        'date_of_birth' => 'required|date',
        'gender' => 'required|string',
        'blood_group' => 'required|string',
        'nationality' => 'required|string|max:255',
        'city' => 'required|string|max:255',
        'passport_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        'class_id' => 'required|exists:classes,id',
        'section_id' => 'required',
        'parent_first_name' => 'required|string|max:255',
        'parent_last_name' => 'required|string|max:255',
        'parent_phone' => 'required|string|max:255',
        'parent_email' => 'required|email|max:255',
    ];

    $validator = validator::make($request->all(), $rules);

    if ($validator->fails()) {
        return redirect()->route('add_student_page')->withErrors($validator)->withInput();
    }

    $user= new User();
    $user->name = $request->first_name.''.$request->last_name;
    $user->email = $request->parent_email;
    $user->phone = $request->parent_phone;
    $user->location = $request->nationality;
    $user->password = bcrypt('student');
    $user->role_id = 3;
    $user->school_id = Auth::user()->school_id;
    $user->save();

    $student_id = $user->id;


    $studentInfo = new StudentInfo();
    $studentInfo->first_name = $request->first_name;
    $studentInfo->last_name = $request->last_name;
    $studentInfo->registration_no = $request->registration_no;
    $studentInfo->date_of_birth = $request->date_of_birth;
    $studentInfo->gender = $request->gender;
    $studentInfo->blood_group = $request->blood_group;
    $studentInfo->nationality = $request->nationality;
    $studentInfo->city = $request->city;

    // Handle file upload
    if ($request->hasFile('passport_photo')) {
        $file = $request->file('passport_photo');
        $filename = time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads'), $filename);
        $studentInfo->passport_photo = $filename;
    }

    $studentInfo->class_id = $request->class_id;
    $studentInfo->section = $request->section_id;
    $studentInfo->parent_first_name = $request->parent_first_name;
    $studentInfo->parent_last_name = $request->parent_last_name;
    $studentInfo->parent_phone = $request->parent_phone;
    $studentInfo->parent_email = $request->parent_email;

    $studentInfo->user_id = $student_id;
    $studentInfo->school_id = Auth::user()->school_id;
    $studentInfo->save();

    return redirect()->route('add_student_page')->with('message', 'Student added successfully!');
}
}
