<?php

namespace App\Http\Controllers;


use App\Models\Assignment;
use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\Driver;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamSubject;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\StudentInfo;
use App\Models\Subject;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $student_info = StudentInfo::with('SchoolClass', 'Section')->where('user_id', Auth::user()->id)
            ->first();
        $events = Event::where('school_id', Auth::user()->school_id)->take(3)->get();
        $assignments = Assignment::where('school_id', Auth::user()->school_id)
        ->where('class_id', $student_info->class_id)
        ->take(3)->get();
        return view('student.index', [
            'student_info' => $student_info,
            'events' => $events,
            'assignments'=>$assignments,
        ]);
    }

    public function events(Request $request)
    {

        $school_id = $request->toJson ? $request->school_id : Auth::user()->school_id;
        $events = Event::where('school_id', $school_id)->paginate(10);

        return $request->toJson ? response()->json([
            'success' => true,
            'data' => [
                'events' => $events,
            ],
        ]) : view('student.events', compact('events'));
    }


    public function dailyTracker(Request $request)
    {

        $student = StudentInfo::where('user_id',$request->user_id)
                   ->where('school_id',$request->school_id)
                   ->first();

        if($student->activity =='' || $student->activity == null){
            $location = 'At Home';
            $activity = 'Waiting for Pickup';
            $supervisor = '';
            $contacts = '';
        }
        else if ($student->activity !='offloadSchool') {
            $driver = Driver::with('user')->where('user_id',$student->driver_id)
            ->where('school_id',$request->school_id)
            ->first();

            $location = 'In the Bus';
            $activity = HelperController::activityMapper($student->activity,'student');
            $supervisor = $driver->user->name;
            $contacts = $driver->user->phone;

        }else{
            $class = SchoolClass::where('id',$student->class_id)
                     ->where('school_id',$student->school_id)
                     ->first();

            $location = 'Arrived At School';
            $activity = 'Attending classes';
            $supervisor = $class->supervisor_name;
            $contacts = $class->supervisor_phone;
            
        }
   
        return response()->json([
            'success' => true,
            'data' => [
                'location' => $location,
                'activity' => $activity,
                'supervisor' => $supervisor,
                'contacts' => $contacts,
            ],
        ]);

    }

    public function aboutMe(Request $request)
    {
        $student_info = StudentInfo::with('SchoolClass', 'section')->where('user_id', Auth::user()->id)
            ->first();


        return view('student.about_me', ['student_info' => $student_info]);
    }

    public function marks(Request $request)
    {
        $user_id = $request->toJson ? $request->user_id : Auth::user()->id;
        $school_id = $request->toJson ? $request->school_id : Auth::user()->school_id;
    
        $student = StudentInfo::where('user_id', $user_id)->first();
        if (!$student && !$request->toJson) {
            return redirect()->back()->with('error', 'Student not found');
        } else if (!$student && $request->toJson) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ]);
        }

        $exam_list = Mark::where('student_id', $student->id)->pluck('exam_id')->unique();
        $exams = Exam::whereIn('id', $exam_list)->get(['id', 'name']);
        $exam = null;
        $marks = collect();
        $students = collect();
        $subjects = collect();
        $grades = Grade::where('school_id', $school_id)->get();
    
        if ($request->has('exam_id')) {
            $exam_id = $request->input('exam_id');
            $exam = Exam::find($exam_id);
    
            if ($exam) {
                $marks = Mark::where('exam_id', $exam_id)
                    ->where('student_id', $student->id)
                    ->with('subject')
                    ->get()
                    ->groupBy('student_id');
    

                $students = StudentInfo::where('id', $student->id)->get();
                foreach ($students as $student) {
                    $studentMarks = $marks->get($student->id, collect());
                    $totalMarks = $studentMarks->sum('marks');
                    $subjectCount = $studentMarks->count();
                    $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
    
                    $student->average = round($average, 2);
                    foreach ($studentMarks as $mark) {
                        $grade = $grades->first(function ($grade) use ($mark) {
                            return $mark->marks >= $grade->from && $mark->marks <= $grade->to;
                        });
                        if ($grade) {
                            $mark->formattedMarks = "{$mark->marks} ({$grade->grade})";
                        } else {
                            $mark->formattedMarks = "{$mark->marks}";
                        }
                    }
                }
    
                $studentIds = $marks->keys();
                $subjects = ExamSubject::with('subjects')->where('exam_id', $exam_id)->get();
            }
        }
    
        return $request->toJson ? response()->json([
            'success' => true,
            'data' => [
                'exams' => $exams,
                'exam' => $exam,
                'marks' => $marks,
                'students' => $students,
                'subjects' => $subjects
            ],
        ]) : view('student.marks', [
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


    public function attendence(Request $request)
    {
        $student_id = $request->toJson ? $request->user_id : Auth::user()->id;
        $student = StudentInfo::where('user_id', $student_id)->first();
        if (!$student && !$request->toJson) {
            return redirect()->back()->with('error', 'Student not found');
        } else if (!$student && $request->toJson) {
            return response()->json([
                'success' => false,
                'error' => 'Student not found'
            ]);
        }
    
        $attendanceData = AttendenceData::where('class_id', $student->class_id)
            ->with('attendance')
            ->select('attendence_id')
            ->distinct()
            ->get();
    

        $attendance = null;
        $attendanceRecords = collect();
        $students = collect();
        $numberPresent = 0;
        $totalClasses = 0;
        $percentage = 0;
        $dateFrom = null;
        $dateTo = now()->format('Y-m-d');
    
        if ($request->has('attendance_id')) {
            $attendance_id = $request->input('attendance_id');
            $attendance = Attendence::find($attendance_id);
    
            if ($attendance) {
                $attendanceRecords = AttendenceData::where('attendence_id', $attendance_id)
                    ->where('class_id', $student->class_id)
                    ->where('student_id', $student->id)
                    ->with('attendance')
                    ->get();
    
                $students = StudentInfo::where('id', $student->id)
                ->select('student_info.*', DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name"))
                ->get();
                $dateFrom = $attendance->created_at->format('Y-m-d');
                $dateFromCarbon = \Carbon\Carbon::parse($dateFrom);
                $dateToCarbon = \Carbon\Carbon::now();
                for ($date = $dateFromCarbon->copy(); $date->lte($dateToCarbon); $date->addDay()) {
                    if ($date->isWeekday()) {
                        $totalClasses++;
                    }
                }

                $weeklyRecords = $attendanceRecords->filter(function ($record) use ($dateFromCarbon, $dateToCarbon) {
                    $recordDate = \Carbon\Carbon::parse($record->date);
                    return $recordDate->between($dateFromCarbon, $dateToCarbon);
                });
    
                $numberPresent = $weeklyRecords->where('status', 'Present')->count();
                $percentage = $totalClasses > 0 ? ($numberPresent / $totalClasses) * 100 : 0;
            }
        }
    
        return $request->toJson ? response()->json([
            'success' => true,
            'data' => [
                'attendanceData' => $attendanceData,
                'attendance' => $attendance,
                'attendanceRecords' => $attendanceRecords,
                'students' => $students,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
                'number_present' => $numberPresent,
                'total_classes' => $totalClasses,
                'percentage' => number_format($percentage, 2)
            ],
        ]) : view('student.attendencePage', [
            'attendanceData' => $attendanceData,
            'attendance' => $attendance,
            'attendanceRecords' => $attendanceRecords,
            'students' => $students,
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'number_present' => $numberPresent,
            'total_classes' => $totalClasses,
            'percentage' => number_format($percentage, 2)
        ]);
    } 


    public function suggestionPage(Request $request)
    {
        return view('student.suggestion_page');
    }

    public function viewAssignments(Request $request)
    {

        $assignment_type = $request->assignment_type == 'daily-task' ? 'home_work' : $request->assignment_type;
        $user_id = $request->toJson ? $request->user_id: Auth::user()->id;

        $student_info = StudentInfo::where('user_id', $user_id)
        ->first();

        $assignments = Assignment::with('sender')
                       ->where('assignment_type',$assignment_type)
                       ->where('school_id', $student_info->school_id)
                       ->where('class_id', $student_info->class_id)
                       ->paginate(10);  

        return  $request->toJson ?  response()->json([
            'success'=>true,
            'data'=>[
                'assignments' => $assignments,
                'assignment_type' => $assignment_type
              ],
        ]) : view('student.assignment_page',compact('assignments','assignment_type'));
          
    }

}
