<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Event;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentInfo;
use App\Models\Subject;
use App\Models\Suggestion;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Teacher;

class SchoolAdminController extends Controller
{
    public function index(Request $request)
    {
        $total_classes = SchoolClass::where('school_id', Auth::user()->school_id)->get()->count();
        $total_students = StudentInfo::where('school_id', Auth::user()->school_id)->get()->count();
        $total_subjects = Subject::where('school_id', Auth::user()->school_id)->get()->count();
        $suggestions = Suggestion::with('school', 'student', 'student.Schoolclass')
            ->where('school_id', Auth::user()->school_id)
            ->take(3)->get();
        $events = Event::where('school_id', Auth::user()->school_id)->take(3)->get();

        return view('school_admin.index', [
            'total_classes' => $total_classes,
            'total_students' => $total_students,
            'total_subjects' => $total_subjects,
            'suggestions' => $suggestions,
            'events' => $events
        ]);
    }

    public function teacherHome(Request $request)
    {
        
        $assignments = Assignment::where('school_id', Auth::user()->school_id)
        ->where('sender_id',Auth::user()->id)
        ->take(3)->get();
        $events = Event::where('school_id', Auth::user()->school_id)->take(3)->get();

        return view('school_admin.teacher_home', [
            'assignments' =>$assignments,
            'events' => $events
        ]);
    }

    public function classPage(Request $request)
    {
        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->paginate(10);
        $all_classes = [];
        foreach ($classes as $class) {
            $total_students = StudentInfo::where('school_id', Auth::user()->school_id)
                ->where('class_id', $class->id)
                ->count();
            array_push($all_classes, (object) [
                'info' => $class,
                'total_students' => $total_students,
            ]);
        }
        return view('school_admin.class_page', ['classes' => $all_classes, 'paginated' => $classes]);
    }

    public function teachersPage(Request $request)
    {
        $teachers = Teacher::with('user')->where('school_id', Auth::user()->school_id)->paginate(10);
        return view('school_admin.teachers_page', ['teachers' => $teachers]);        
    }

    public function examinationsPage(Request $request)
    {
        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();
        $exams = Exam::where('school_id', Auth::user()->school_id)->paginate(10);

        return view('school_admin.examinations_page', ['exams' => $exams, 'classes' => $classes, 'subjects' => $subjects]);
    }

    public function gradesPage(Request $request)
    {

        $grades = Grade::where('school_id',Auth::user()->school_id)->paginate(10);
        return view('school_admin.grades_page', ['grades' => $grades]);
    }

    public function addGrade(Request $request)
    {

        $rules = [
            'from' => 'required|numeric|min:0|max:100',
            'to' => 'required|numeric|min:0|max:100|gt:from',
            'grade' => 'required|string|max:255',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return redirect()->route('all_grades_page')->withErrors($validator)->withInput();
        }

        if (
            Grade::where('school_id', Auth::user()->school_id)
                ->where('from', '=', $request->from)
                ->where('to', '=', $request->to)
                ->exists()
        ) {
            return redirect()->route('all_grades_page')->withErrors('Similar grades already exists!')->withInput();
        }

        $grade = new Grade();
        $grade->from = $request->from;
        $grade->to = $request->to;
        $grade->grade = $request->grade;
        $grade->school_id = Auth::user()->school_id;
        $grade->save();
        return redirect()->route('all_grades_page')->with('message', 'Grade added successfully!');

    }

    public function subjectsPage(Request $request)
    {
        $subjects = Subject::where('school_id', Auth::user()->school_id)->paginate(10);
        return view('school_admin.subjects_page', ['subjects' => $subjects]);
    }

    public function studentsPage(Request $request)
    {

        $student_info = StudentInfo::with('SchoolClass', 'user')
            ->select('student_info.*', DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name"))
            ->where('school_id', Auth::user()->school_id)
            ->paginate(10);

        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
        return view('school_admin.students_page', ['students' => $student_info, 'classes' => $classes]);
    }
    public function addClass(Request $request)
    {
        try {
            $rules = [
                'class_name' => 'required|string|max:255',
            ];

            $validator = \Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return redirect()->route('add_class_page')->withErrors($validator)->withInput();
            }
            $class = new SchoolClass();
            $class->name = $request->input('class_name');
            $class->school_id = Auth::user()->school_id;
            $class->save();
            return redirect()->route('add_class_page')->with('message', 'Class added successfully!');
        } catch (\Exception $e) {
            return redirect()->route('add_class_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }
    }
    public function addStudent(Request $request)
    {
        $rules = [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'registration_no' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'blood_group' => 'required|string',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'class_id' => 'required|exists:classes,id',
            'parent_first_name' => 'required|string|max:255',
            'parent_last_name' => 'required|string|max:255',
            'parent_phone' => ['required', 'string', 'regex:/^0\d{9}$/'],
        ];

        $customMessages = [
            'parent_phone.regex' => 'Enter a 10-digit number starting with 0 for the parent\'s phone.',
        ];

        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->route('add_student_page')->withErrors($validator)->withInput();
        }

        if (
            StudentInfo::where('school_id', Auth::user()->school_id)
                ->where('registration_no', '=', $request->registration_no)->exists()
        ) {
            return redirect()->route('add_student_page')->withErrors('Registration No. Already exists!')->withInput();
        }

     
        $student_email = HelperController::emailAssigner(3);

        $newUser = new User();
        $newUser->name = strtolower($request->first_name . ' ' . $request->middle_name . ' ' . $request->last_name);
        $newUser->email = $student_email;
        $newUser->phone = $request->parent_phone;
        $newUser->location = $request->nationality;
        $newUser->password = bcrypt('student');
        $newUser->role_id = 3;
        $newUser->isActive = true;
        $newUser->school_id = Auth::user()->school_id;
        $newUser->save();

        $studentInfo = new StudentInfo();
        $studentInfo->first_name = strtolower($request->first_name);
        $studentInfo->middle_name = strtolower($request->middle_name);
        $studentInfo->last_name = strtolower($request->last_name);
        $studentInfo->registration_no = $request->registration_no;
        $studentInfo->date_of_birth = $request->date_of_birth;
        $studentInfo->gender = $request->gender;
        $studentInfo->blood_group = $request->blood_group;
        $studentInfo->nationality = $request->nationality;
        $studentInfo->city = strtolower($request->city);
        $studentInfo->passport_photo = null;
        $studentInfo->class_id = $request->class_id;
        $studentInfo->parent_first_name = strtolower($request->parent_first_name);
        $studentInfo->parent_last_name = strtolower($request->parent_last_name);
        $studentInfo->parent_phone = $request->parent_phone;
        $studentInfo->parent_email = $student_email;
        $studentInfo->school_id = Auth::user()->school_id;
        $studentInfo->user_id = $newUser->id;
        $studentInfo->save();

        return redirect()->route('add_student_page')->with('message', 'Student added successfully!');
    }

    public function getStreams($classId)
    {
        $sections = Section::where('class_id', $classId)
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


    public function addSubject(Request $request)
    {

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
        ]);

        $subject = new Subject();
        $subject->name = $request->subject_name;
        $subject->short_name = $request->short_name;
        $subject->school_id = Auth::user()->school_id;
        $subject->save();

        return redirect()->route('all_subjects_page')->with('message', 'Subject added successfully!');
    }

    public function addTeacher(Request $request)
    {

        $rules = [
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'regex:/^0\d{9}$/']
        ];

        $customMessages = [
            'phone_number.regex' => 'Enter a 10-digit number starting with 0 for the teacher\'s phone.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->route('all_teachers_page')->withErrors($validator)->withInput();
        }

        $user_email = HelperController::emailAssigner(4);
        $full_name = strtolower ("{$request->first_name} {$request->second_name} {$request->last_name}");


        $user = new User();
        $user->name = $full_name;
        $user->email = $user_email;
        $user->phone = $request->phone_number;
        $user->isActive = 1;
        $user->gender = $request->gender;
        $user->location = $request->nationality;
        $user->password = bcrypt('teacher');
        $user->role_id = 4;
        $user->school_id = Auth::user()->school_id;
        $user->save();

        $teacher_id = $user->id;

        $TeacherInfo = new Teacher();
        $TeacherInfo->first_name = strtolower($request->first_name);
        $TeacherInfo->second_name = strtolower($request->second_name);
        $TeacherInfo->last_name = strtolower($request->last_name);
        $TeacherInfo->phone_number = $request->phone_number;
        $TeacherInfo->email = $user_email;
        $TeacherInfo->date_of_birth = $request->date_of_birth;
        $TeacherInfo->gender = $request->gender;
        $TeacherInfo->nationality = $request->nationality;
        $TeacherInfo->city = $request->city;
        $TeacherInfo->school_id = Auth::user()->school_id;
        $TeacherInfo->user_id = $teacher_id;
        $TeacherInfo->save();


        return redirect()->route('all_teachers_page')->with('message', 'Teacher added successfully!');

    }

    public function viewSuggestions(Request $request)
    {
        $suggestions = Suggestion::with('school', 'student', 'student.Schoolclass')
            ->where('school_id', Auth::user()->school_id)
            ->paginate(10);

        return view('school_admin.viewSuggestions', ['suggestions' => $suggestions]);
    }

    public function promoteClass(Request $request)
    {
        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
        return view('school_admin.promoteClass', ['classes' => $classes]);
    }

    public function filterStudents($name)
    {

        $student_info = StudentInfo::with('SchoolClass', 'user')
            ->where('school_id', Auth::user()->school_id)
            ->where(function ($query) use ($name) {
                $query->where('first_name', 'LIKE', "%{$name}%")
                    ->orWhere('middle_name', 'LIKE', "%{$name}%")
                    ->orWhere('registration_no', 'LIKE', "%{$name}%")
                    ->orWhere('email', 'LIKE', "%{$name}%")
                    ->orWhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'LIKE', "%{$name}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$name}%");
            })
            ->select('student_info.*', DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name"))
            ->paginate(10);


        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
        return response()->json([$student_info, $classes]);
    }

    public function filterTeachers($name)
    {

        $teachers = Teacher::with('user')
            ->where('school_id', Auth::user()->school_id)
            ->where(function ($query) use ($name) {
                $query->where('first_name', 'LIKE', "%{$name}%")
                    ->orWhere('second_name', 'LIKE', "%{$name}%")
                    ->orWhere('email', 'LIKE', "%{$name}%")
                    ->orWhere('last_name', 'LIKE', "%{$name}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', second_name, ' ', last_name)"), 'LIKE', "%{$name}%")
                    ->orWhere(DB::raw("CONCAT(first_name, ' ', last_name)"), 'LIKE', "%{$name}%");
            })
            ->select('teachers.*', DB::raw("CONCAT(first_name, ' ', second_name, ' ', last_name) as full_name"))
            ->paginate(10);

        return response()->json([$teachers]);
    }

    public function handlePromotion(Request $request)
    {
        try {
            $rules = [
                'class_id' => 'required',
                'promoted_to' => 'required',
            ];

            $validator = \Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return redirect()->route('promote_class')->withErrors($validator)->withInput();
            }

            StudentInfo::where('class_id', $request->class_id)
                ->where('school_id', Auth::user()->school_id)
                ->update(['class_id' => $request->promoted_to]);

            return redirect()->route('promote_class')->with('message', 'Class promoted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('promote_class')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }
    }



}
