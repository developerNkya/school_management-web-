<?php

namespace App\Http\Controllers;

use App\Models\Attendence;
use Illuminate\Http\Request;

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
}
