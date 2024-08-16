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
    
        return redirect()->back()->with('success', 'Attendance created successfully.');
    }
    // public function showAddAttendenceForm()
    // {
    //     $schoolId = Auth::user()->school_id;
        
    //     // Fetch data based on the school_id
    //     $attendances = Attendence::where('school_id', $schoolId)->get();
    //     $classes = SchoolClass::where('school_id', $schoolId)->get();
    //     $sections = Section::where('school_id', $schoolId)->get();
    //     $subjects = Subject::where('school_id', $schoolId)->get();
        
    //     // Default empty students collection
    //     $students = collect();
        
    //     return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students'));
    // }
    
    // public function fetchStudents(Request $request)
    // {
    //     $schoolId = Auth::user()->school_id;
    
    //     // Fetch data based on the school_id
    //     $attendances = Attendence::where('school_id', $schoolId)->get();
    //     $classes = SchoolClass::where('school_id', $schoolId)->get();
    //     $sections = Section::where('school_id', $schoolId)->get();
    //     $subjects = Subject::where('school_id', $schoolId)->get();
        
    //     // Fetch students if form is submitted
    //     $students = StudentInfo::where('class_id', $request->input('class_id'))
    //                            ->where('section_id', $request->input('section_id'))
    //                            ->get();
        
    //     return view('school_admin.add_attendence', compact('attendances', 'classes', 'sections', 'subjects', 'students'));
    // }
    
    // public function saveAttendence(Request $request)
    // {
    //     // Validation logic
    //     $request->validate([
    //         'attendance_id' => 'required|exists:attendances,id',
    //         'class_id' => 'required|exists:classes,id',
    //         'section_id' => 'required|exists:sections,id',
    //         'date' => 'required|date',
    //         'status.*' => 'required|in:Present,Absent,Allowed,Sick',
    //         'total_appearance.*' => 'required|integer',
    //         'percentage.*' => 'required|numeric',
    //         'subject_id' => 'nullable|exists:subjects,id',
    //     ]);
    
    //     foreach ($request->input('status') as $studentId => $status) {
    //         AttendenceData::create([
    //             'attendence_id' => $request->input('attendance_id'),
    //             'student_id' => $studentId,
    //             'class_id' => $request->input('class_id'),
    //             'section_id' => $request->input('section_id'),
    //             'subject_id' => $request->input('subject_id', null),
    //             'status' => $status,
    //             'total_appearance' => $request->input('total_appearance')[$studentId],
    //             'percentage' => $request->input('percentage')[$studentId],
    //             'date' => $request->input('date'),
    //         ]);
    //     }
    
    //     return redirect()->back()->with('success', 'Attendance recorded successfully.');
    // }
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
        
        // Fetch students if form is submitted
        $students = StudentInfo::where('class_id', $request->input('class_id'))
                               ->where('section', $request->input('section_id'))
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
            'total_appearance.*' => 'required|integer',
            'percentage.*' => 'required|numeric',
            'subject_id' => 'nullable|exists:subjects,id',
        ]);
    
        foreach ($request->input('status') as $studentId => $status) {
            AttendenceData::create([
                'attendence_id' => $request->input('attendance_id'),
                'student_id' => $studentId,
                'class_id' => $request->input('class_id'),
                'section_id' => $request->input('section_id'),
                'subject_id' => $request->input('subject_id', null),
                'school_id' => Auth::user()->school_id,
                'status' => $status,
                'total_appearance' => $request->input('total_appearance')[$studentId],
                // 'percentage' => $request->input('percentage')[$studentId],
                'percentage' => $request->input('percentage'),
                'date' => $request->input('date'),
            ]);
        }
    

        return redirect()->route('attendance.addAttendence')->with('message', 'Attendance recorded successfully.');
    }
        
    
    


}
