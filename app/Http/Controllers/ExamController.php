<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use Illuminate\Http\Request;
use App\Models\Exam;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Mark;
use Illuminate\Support\Facades\Auth;

class ExamController extends Controller
{

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'classes' => 'required|array',
            'subjects' => 'required|array',
            'start_date' => 'required|date',
        ]);

        $exam = Exam::create([
            'name' => $request->input('name'),
            'start_date' => $request->input('start_date'),
            'school_id' =>Auth::user()->school_id
        ]);

        // Attach classes and subjects
        $exam->classes()->attach($request->input('classes'));
        $exam->subjects()->attach($request->input('subjects'));

        return redirect()->route('all_exam_page')->with('message', 'Exam added successfully!');
    }

    public function inputMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'student_id' => 'required|integer',
            'marks' => 'required|array',
            'marks.*.subject_id' => 'required|exists:subjects,id',
            'marks.*.marks' => 'required|integer',
        ]);

        foreach ($request->input('marks') as $markData) {
            Mark::updateOrCreate(
                [
                    'exam_id' => $request->input('exam_id'),
                    'student_id' => $request->input('student_id'),
                    'subject_id' => $markData['subject_id'],
                ],
                ['marks' => $markData['marks']]
            );
        }
        return redirect()->back()->with('success', 'Marks inputted successfully.');
    }
    
    public function marks(Request $request)
{

    $schoolId = Auth::user()->school_id;
    $exams = Exam::where('school_id', $schoolId)->get();

    $classes = collect();
    $subjects = collect();
    $selectedExamId = $request->input('exam_id');

    if ($selectedExamId) {
        $selectedExam = Exam::where('id', $selectedExamId)
                            ->where('school_id', $schoolId)
                            ->with('classes', 'subjects')
                            ->first();
        if ($selectedExam) {
            $classes = $selectedExam->classes;
            $subjects = $selectedExam->subjects;
        }
    }

    return view('school_admin.marks', compact('classes', 'exams', 'subjects', 'selectedExamId'));
}

}
