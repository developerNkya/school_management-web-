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
        $subjects = Subject::where('school_id', Auth::user()->school_id)->get();
        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
    
        $assignmentsQuery = Assignment::with('subject', 'class', 'sender', 'school')
            ->where('school_id', Auth::user()->school_id);
    
        $assignmentsQuery->when(Auth::user()->role_id == 4, function($query) {
            return $query->where('sender_id', Auth::user()->id);
        });
    
        $assignments = $assignmentsQuery->paginate(10);  
        $totalSize = $assignmentsQuery->sum('file_size');
    
        return view('school_admin.assignment_page', compact('subjects', 'classes', 'assignments', 'totalSize'));
    }
    

    public function saveAssignment(Request $request)
    {
        $validated = $request->validate(
            [
                'assignment_name' => 'required|string|max:255',
                'subject_id' => 'required|exists:subjects,id',
                'class_id' => 'required|exists:classes,id',
                'submission_date' => 'required|date|after_or_equal:today',
                'assignment_file' => 'required|mimes:pdf|max:1024'
            ],
            [
                'assignment_file.required' => 'Please upload a file.',
                'assignment_file.max' => 'The uploaded file should not be more than 1MB!',
                'assignment_file.mimes' => 'The apploaded file should be in form of PDF!',
            ]
        );
        
    

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
                $assignment->assignment_type = $request->assignment_type;
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

        if (Storage::disk('public')->exists($filePath)) {
            $fileName = basename($filePath);
            if (!$request->toJson) {
                return Storage::disk('public')->download($filePath, $fileName);
            }

            $fileContents = Storage::disk('public')->get($filePath);
            return response()->json([
                'file' => base64_encode($fileContents),
                'file_name' => $fileName
            ]);
        } else {
            return response()->json(['error' => 'File not found.'], 404);
        }
    }


}
