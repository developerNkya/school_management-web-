<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentInfo;
use App\Models\Subject;
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
        
        // Fetch data based on the school_id
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
        
        // Default empty students collection
        $students = collect();
        
        return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students'));
    }
    
    public function fetchStudents(Request $request)
    {
        $schoolId = Auth::user()->school_id;
    
        // Fetch data based on the school_id
        $attendances = Attendence::where('school_id', $schoolId)->get();
        $classes = SchoolClass::where('school_id', $schoolId)->get();
        $sections = Section::where('school_id', $schoolId)->get();
        $subjects = Subject::where('school_id', $schoolId)->get();
    
        // Fetch students with their attendance data filtered by date
        $students = StudentInfo::where('class_id', $request->input('class_id'))
                               ->where('section', $request->input('section_id'))
                               ->with(['attendances' => function ($query) use ($request) {
                                   $query->where('attendence_id', $request->input('attendence_id'))
                                         ->whereDate('date', $request->input('date'));
                               }])
                               ->get();
    
        return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students'));
    }
    
    
    
    public function saveAttendence(Request $request)
    {
        // Validation logic
        $request->validate([
            'attendance_id' => 'required|exists:attendances,id',
            'class_id' => 'required|exists:classes,id',
            'section_id' => 'required|exists:sections,id',
            'date' => 'required|date',
            'status.*' => 'required|in:Present,Absent,Allowed,Sick',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);
    
        foreach ($request->input('status') as $studentId => $status) {
            // Fetch existing attendance record
            $attendanceData = AttendenceData::where('attendence_id', $request->input('attendance_id'))
                                            ->where('student_id', $studentId)
                                            ->where('date', $request->input('date'))
                                            ->first();
    
            // Determine total appearance
            if ($attendanceData) {
                $totalAppearance = $attendanceData->total_appearance;
            } else {
                $totalAppearance = AttendenceData::where('attendence_id', $request->input('attendance_id'))
                                                 ->where('student_id', $studentId)
                                                 ->count();
            }
    
            // Adjust total appearance based on status
            if ($status === 'Absent') {
                $totalAppearance = max(0, $totalAppearance - 1); // Ensure it doesn't go below 0
            } else {
                $totalAppearance += 1;
            }
    
            // Calculate percentage based on appearance and days
            $totalDays = AttendenceData::where('attendence_id', $request->input('attendance_id'))
                                       ->where('student_id', $studentId)
                                       ->distinct()
                                       ->count('date');
            $totalDays = $totalDays > 0 ? $totalDays : 1; // Ensure totalDays is at least 1
    
            $percentage = ($totalAppearance / $totalDays) * 100;
    
            // Update or create the attendance data record
            if ($attendanceData) {
                $attendanceData->update([
                    'status' => $status,
                    'total_appearance' => $totalAppearance,
                    'percentage' => round($percentage, 2),
                ]);
            } else {
                AttendenceData::create([
                    'attendence_id' => $request->input('attendance_id'),
                    'student_id' => $studentId,
                    'class_id' => $request->input('class_id'),
                    'section_id' => $request->input('section_id'),
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
