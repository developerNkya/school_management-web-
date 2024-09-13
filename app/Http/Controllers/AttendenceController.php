<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentInfo;
use App\Models\Subject;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendenceController extends Controller
{
    public function createNew(Request $request)
    {
        return view('school_admin.create_attendence');
    }

    public function newAttendence(Request $request)
    {
        $request->validate([
            'attendance_name' => 'required|string|max:255',
            'academic_year' => 'required|string|max:9',
            'attendance_type' => 'required|in:per_day,per_subject',
            'school_id' => 'required|exists:schools,id',
        ]);

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

    public function fetchStudents(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();

        $students = StudentInfo::where('class_id', $request->input('class_id'))
            ->with([
                'attendances' => function ($query) use ($request) {
                    $query->where('attendence_id', $request->input('attendence_id'))
                        ->whereDate('date', $request->input('date'));
                }
            ])
            ->get();

    $attendenceDays = 0;
    $day = '';
    if ($request->input('attendence_id') != null) {
        $day = $request->input('date');
        $attendence = Attendence::where('id', $request->input('attendence_id'))
                     ->where('created_at','<',$day)
                     ->first();        
    
        if ($attendence) {
            $createdAt = Carbon::parse($attendence->created_at);
            $now = Carbon::now();
            $attendenceDays = $this->countWeekdays($createdAt, $now);
        }else{
            return redirect()->back()->withErrors(['error' => 'Date Should be greater than the date to which Attendence was created!.'])->withInput();
        }
    }
        return view('school_admin.add_attendence', compact('day','attendenceDays','attendances', 'classes', 'sections', 'subjects', 'students'));
    }


    function countWeekdays($startDate, $endDate) {
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
            'date' => 'required|date',
            'status.*' => 'required|in:Present,Absent,Allowed,Sick',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);

        foreach ($request->input('status') as $studentId => $status) {
            $attendanceData = AttendenceData::where('attendence_id', $request->input('attendence_id'))
                ->where('student_id', $studentId)
                ->where('date', $request->input('date'))
                ->first();

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
