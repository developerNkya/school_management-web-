<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\School;
use App\Models\SchoolClass;
use App\Models\Subject;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class AssignmentController extends Controller
{
    public function addAssignment(Request $request)
    {
        $subjects = Subject::where('school_id',Auth::user()->school_id)->get();
        $classes = SchoolClass::where('school_id',Auth::user()->school_id)->get();
        $assignments = Assignment::with('subject','class','sender','school')
                       ->where('school_id',Auth::user()->school_id)->paginate(10);
        $totalSize = Assignment::where('school_id', Auth::user()->school_id)
                ->sum('file_size');
        return view('school_admin.assignment_page',compact('subjects','classes','assignments','totalSize'));
    }

    public function saveAssignment(Request $request)
    {
        $validated = $request->validate([
            'assignment_name' => 'required|string|max:255',
            'subject_id' => 'required|exists:subjects,id',
            'class_id' => 'required|exists:classes,id',
            'submission_date' => 'required|date'
        ]);
    
        if ($request->hasFile('assignment_file')) {
            $file = $request->file('assignment_file');
            $fileSize = ceil($file->getSize() / 1024 / 1024);
    
            $totalSize = Assignment::where('school_id', Auth::user()->school_id)
                ->sum('file_size');
    
             $maxAllowedSize = 20; 
            if (($totalSize + $fileSize) > $maxAllowedSize) {
                return redirect()->back()->withErrors(['error' => 'Space full: You have exceeded the 20MB limit for assignments.'])->withInput();
            }

            $filename = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('assignments', $filename, 'public');
    
            try {
                $assignment = new Assignment();
                $assignment->name = $request->assignment_name;
                $assignment->subject_id = $request->subject_id;
                $assignment->class_id = $request->class_id;
                $assignment->sender_id = Auth::user()->id;
                $assignment->school_id = Auth::user()->school_id;
                $assignment->submission_date = $request->submission_date;
                $assignment->file_path = $filePath;
                $assignment->file_size = $fileSize;
                $assignment->save();
    
                return redirect()->back()->with('message', 'Assignment saved successfully!');
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'An error occurred while saving the assignment: ' . $e->getMessage()])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['error' => 'File upload failed. Please try again.'])->withInput();
        }
    }
    
    public function downloadFile(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:assignments,id',
        ]);

        $assignment = Assignment::findOrFail($request->id);

        $filePath = $assignment->file_path;
        $fileName = basename($filePath);

        if (Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        } else {
            return redirect()->back()->withErrors(['error' => 'File not found.']);
        }
    } 
    
}
