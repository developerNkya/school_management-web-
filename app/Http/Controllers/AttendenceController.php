<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentInfo;
use App\Models\Subject;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendenceController extends Controller
{
    public function createNew(Request $request)
    {
       $attendences =  Attendence::where('school_id',Auth::user()->school_id)
        ->paginate(10);
        return view('school_admin.create_attendence',compact('attendences'));
    }

    public function newAttendence(Request $request)
    {
        $request->validate([
            'attendance_name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:9',
            'attendance_type' => 'required|in:per_day,per_subject',
            'school_id' => 'required|exists:schools,id',
        ]);

        if (
            Attendence::where('attendance_name', $request->input('attendance_name'))
                ->where('school_id', Auth::user()->school_id)
                ->exists()
        ) {
            return redirect()->back()->withErrors(['error' => 'Attendance name provided is already used!']);
        }

        Attendence::create([
            'attendance_name' => $request->input('attendance_name'),
            'academic_year' => $request->input('academic_year'),
            'attendance_type' => $request->input('attendance_type'),
            'school_id' => $request->input('school_id'),
        ]);

        return redirect()->back()->with('message', 'Attendance created successfully.');
    }
    public function showAddAttendenceForm()
    {
        $schoolId = Auth::user()->school_id;
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        $students = collect();

        return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students'));
    }

    public function showForm()
    {
        $schoolId = Auth::user()->school_id;
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
    
        $students = session('students', []);
        $attendenceDays = session('attendenceDays', 0);
        $createdAt = session('createdAt', null);
        $day = session('day', null);
        $attendence_id = session('attendence_id', null);
        $class_id = session('class_id', null);
        $section_id = session('section_id', null);
        $subject_id = session('subject_id', null);
    
        return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students', 'attendenceDays', 'createdAt', 'day', 'attendence_id', 'class_id', 'section_id', 'subject_id'));
    }
    
    

    public function fetchStudents(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        $students = StudentInfo::with([
            'attendances' => function ($query) use ($request) {
                $query->where('attendence_id', $request->input('attendence_id'))
                    ->whereDate('date', $request->input('date'));
            }
        ])
        ->select('student_info.*', DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name"))
        ->where('class_id', $request->input('class_id'))
        ->get();    
    
        $attendenceDays = 0;
        $day = '';
        $createdAt = null;
    
        if ($request->input('attendence_id') != null) {
            $day = $request->input('date');
            $attendence = Attendence::where('id', $request->input('attendence_id'))
                ->whereDate('created_at', '<=', $day)
                ->first();
            if ($attendence) {
                $createdAt = Carbon::parse($attendence->created_at);
                $now = Carbon::now();
                $attendenceDays = $this->countWeekdays($createdAt, $now);
            } else {
                return redirect()->back()->withErrors(['error' => 'Date should be equal or greater than the date to which attendance was created!'])->withInput();
            }
        }
    
        return redirect()->route('attendance.form')
            ->with([
                'createdAt' => $createdAt,
                'day' => $day,
                'attendenceDays' => $attendenceDays,
                'attendances' => $attendances,
                'classes' => $classes,
                'sections' => $sections,
                'subjects' => $subjects,
                'students' => $students,
                'attendence_id' => $request->input('attendence_id'),
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id'), 
                'subject_id' => $request->input('subject_id'),  
            ])->withInput($request->all());
    }
    

    function countWeekdays($startDate, $endDate)
    {
        $totalDays = 0;

        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            if ($currentDate->isWeekday()) {
                $totalDays++;
            }
            $currentDate->addDay();
        }

        return $totalDays;
    }



    public function saveAttendence(Request $request)
    {
        $request->validate([
            'attendence_id' => 'required|exists:attendances,id',
            'class_id' => 'required|exists:classes,id',
            'date' => 'required|date|before_or_equal:today',
            'status.*' => 'required|in:Present,Absent,Allowed,Sick',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        foreach ($request->input('status') as $studentId => $status) {
            $attendanceData = AttendenceData::where('attendence_id', $request->input('attendence_id'))
                ->where('student_id', $studentId)
                ->where('date', $request->input('date'))
                ->first();

            if ($attendanceData && $attendanceData->status === $status) {
                continue;
            }

            if ($attendanceData) {
                $totalAppearance = $attendanceData->total_appearance;
            } else {
                $totalAppearance = AttendenceData::where('attendence_id', $request->input('attendence_id'))
                    ->where('student_id', $studentId)
                    ->count();
            }

            if ($status === 'Absent') {
                $totalAppearance = max(0, $totalAppearance - 1);
            } else {
                $totalAppearance += 1;
            }

            $totalDays = AttendenceData::where('attendence_id', $request->input('attendence_id'))
                ->where('student_id', $studentId)
                ->distinct()
                ->count('date');
            $totalDays = $totalDays > 0 ? $totalDays : 1;
            $percentage = ($totalAppearance / $totalDays) * 100;

            if ($attendanceData) {
                $attendanceData->update([
                    'status' => $status,
                    'total_appearance' => $totalAppearance,
                    'percentage' => round($percentage, 2),
                ]);
            } else {
                AttendenceData::create([
                    'attendence_id' => $request->input('attendence_id'),
                    'student_id' => $studentId,
                    'class_id' => $request->input('class_id'),
                    'subject_id' => $request->input('subject_id', null),
                    'school_id' => Auth::user()->school_id,
                    'status' => $status,
                    'total_appearance' => $totalAppearance,
                    'percentage' => round($percentage, 2),
                    'date' => $request->input('date'),
                ]);
            }
        }

        return redirect()->route('attendance.addAttendence')->with('message', 'Attendance recorded successfully.');
    }

}
