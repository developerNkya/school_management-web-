<?php

namespace App\Http\Controllers;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentInfo;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

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

    public function teachersPage(Request $request)
    {
        $teachers = Teacher::where('school_id',Auth::user()->school_id)->get();
        return view('school_admin.teachers_page',['teachers'=>$teachers]);
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
                'class_name' => 'required|string|max:255',
                'sections.*' => 'nullable|string|max:255',
                'subjects.*' => 'nullable|string|max:255',
            ];
    
            $validator = \Validator::make($request->all(), $rules);
    
            if ($validator->fails()) {
                return redirect()->route('add_class_page')->withErrors($validator)->withInput();
            }
    
            // Create a new class record
            $class = new SchoolClass();
            $class->name = $request->input('class_name');
            $class->school_id = Auth::user()->school_id;
            $class->subjects = json_encode($request->input('subjects'));
            $class->save();
    
            // Retrieve the class ID
            $class_id = $class->id;
    
            // Create sections
            $allSections = $request->input('sections', []);
    
            foreach ($allSections as $sectionName) {
                if ($sectionName) { // Only create sections with non-empty names
                    $section = new Section();
                    $section->name = $sectionName;
                    $section->class_id = $class_id;
                    $section->school_id = Auth::user()->school_id;
                    $section->save();
                }
            }
    
            return redirect()->route('add_class_page')->with('message', 'Class added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('add_class_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
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
        'section' => 'required',
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
    $studentInfo->section = $request->section;
    $studentInfo->parent_first_name = $request->parent_first_name;
    $studentInfo->parent_last_name = $request->parent_last_name;
    $studentInfo->parent_phone = $request->parent_phone;
    $studentInfo->parent_email = $request->parent_email;

    $studentInfo->user_id = $student_id;
    $studentInfo->school_id = Auth::user()->school_id;
    $studentInfo->save();

    return redirect()->route('add_student_page')->with('message', 'Student added successfully!');
}

public function getStreams($classId)
{
    $sections = Section::where('class_id',$classId)
                ->get();
    if ($sections) {
        return response()->json(['streams' => json_decode($sections)]);
    }
    return response()->json(['streams' => []]);
}

public function getSubjects($classId, $stream)
{
    $class = SchoolClass::find($classId);
    if ($class) {
        return response()->json(['subjects' => json_decode($class->subjects)]);
    }
    return response()->json(['subjects' => []]);
}



    public function addTeacher(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $user= new User();
        $user->name = $request->first_name.''.$request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone_number;
        $user->location = $request->nationality;
        $user->password = bcrypt('teacher');
        $user->role_id = 4;
        $user->school_id = Auth::user()->school_id;
        $user->save();
    
        $teacher_id = $user->id;

        $TeacherInfo = new Teacher();
        $TeacherInfo->first_name = $request->first_name;
        $TeacherInfo->second_name = $request->second_name;
        $TeacherInfo->last_name = $request->last_name;
        $TeacherInfo->phone_number = $request->phone_number;
        $TeacherInfo->email = $request->email;
        $TeacherInfo->date_of_birth = $request->date_of_birth;
        $TeacherInfo->gender = $request->gender;
        $TeacherInfo->nationality = $request->nationality;
        $TeacherInfo->city = $request->city;
        $TeacherInfo->school_id = Auth::user()->school_id;
        $TeacherInfo->user_id = $teacher_id;
        $TeacherInfo->save();
    

        return redirect()->route('all_teachers_page')->with('message', 'Teacher added successfully!');
    }


}
