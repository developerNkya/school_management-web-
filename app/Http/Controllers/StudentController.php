<?php

namespace App\Http\Controllers;


use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\Mark;
use App\Models\StudentInfo;
use App\Models\Subject;
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
        $student_info = StudentInfo::with('SchoolClass', 'section')->where('user_id', Auth::user()->id)
            ->first();


        return view('student.about_me', ['student_info' => $student_info]);
    }

    public function marks(Request $request)
    {
        $user_id = $request->toJson ? $request->user_id: Auth::user()->id;

        $student = StudentInfo::where('user_id', $user_id)->first();
        if (!$student && !$request->toJson) {
            return redirect()->back()->with('error', 'Student not found');
        }else if(!$student && $request->toJson){
            return response()->json([
                'success'=>false,
                'message'=>'Student not found'
            ]); 
        }
        $exam_list = Mark::where('student_id', $student->id)->pluck('exam_id')->unique();
        $exams = Exam::whereIn('id', $exam_list)->get(['id', 'name']);
        $exam = null;
        $marks = collect();
        $students = collect();
        $subjects = collect();

        if ($request->has('exam_id')) {
            $exam_id = $request->input('exam_id');
            $exam = Exam::find($exam_id);

            if ($exam) {
                $marks = Mark::where('exam_id', $exam_id)
                    ->where('student_id', $student->id)
                    ->with('subject')
                    ->get()
                    ->groupBy('student_id');

                $studentIds = $marks->keys();
                $students = StudentInfo::where('id', $student->id)->get();
                $subjects = ExamSubject::with('subjects')->where('exam_id', $exam_id)->get();
            }
        }

        if ($request->toJson) {
            return response()->json([
                'success'=>true,
                'data'=>[
                    'exams' => $exams,
                    'exam' => $exam,
                    'marks' => $marks,
                    'students' => $students,
                    'subjects' => $subjects
                  ],
            ]); 
        }

        return view('student.marks', [
            'exams' => $exams,
            'exam' => $exam,
            'marks' => $marks,
            'students' => $students,
            'subjects' => $subjects
        ]);
    }



    public function viewMarks(Request $request)
    {
        $exam_id = $request->input('exam_id');
        $exam = Exam::find($exam_id);

        if (!$exam) {
            return redirect()->back()->with('error', 'Exam not found.');
        }

        $marks = Mark::where('exam_id', $exam_id)
            ->with('subject')
            ->get()
            ->groupBy('student_id');

        $students = StudentInfo::whereIn('id', $marks->keys())->get();
        $subjects = Subject::all();
        foreach ($students as $student) {
            $studentMarks = $marks->get($student->id);
            $totalMarks = $studentMarks->sum('marks_obtained');
            $subjectCount = $subjects->count();
            $student->average = $subjectCount ? $totalMarks / $subjectCount : 0;
            $student->position = 1;
        }
        return view('student.marks', [
            'exam' => $exam,
            'marks' => $marks,
            'students' => $students,
            'subjects' => $subjects
        ]);
    }

    // public function attendence(Request $request)
    // {
    //     return view('student.attendencePage');
    // }

    public function attendence(Request $request)
    {
        $student = StudentInfo::where('user_id', Auth::user()->id)->first();
        if (!$student) {
            return redirect()->back()->with('error', 'Student not found');
        }

        // Fetch attendance records where class_id matches the student's class_id
        $attendanceData = AttendenceData::where('class_id', $student->class_id)
        ->with('attendance')
        ->select('attendence_id')
        ->distinct()
        ->get();
    

        $attendance = null;
        $attendanceRecords = collect();
        $students = collect();

        if ($request->has('attendance_id')) {
            $attendance_id = $request->input('attendance_id');
            $attendance = Attendence::find($attendance_id);

            if ($attendance) {
                $attendanceRecords = AttendenceData::where('attendence_id', $attendance_id)
                    ->where('class_id', $student->class_id)
                    ->where('student_id', $student->id)
                    ->with('attendance')
                    ->get();

                $students = StudentInfo::where('id', $student->id)->get();
            }
        }

        return view('student.attendencePage', [
            'attendanceData' => $attendanceData,
            'attendance' => $attendance,
            'attendanceRecords' => $attendanceRecords,
            'students' => $students
        ]);
    }


    public function suggestionPage(Request $request)
    {
        return view('student.suggestion_page');
    }

}
