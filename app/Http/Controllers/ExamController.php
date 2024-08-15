<?php

namespace App\Http\Controllers;

use App\Models\SchoolClass;
use App\Models\StudentInfo;
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
            'school_id' => Auth::user()->school_id,
        ]);
    
        $exam->classes()->attach($request->input('classes'));
        $exam->subjects()->attach($request->input('subjects'));
        return redirect()->route('all_exam_page')->with('message', 'Exam added successfully!');
    }
    

    public function addMarks(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
            'subject_id' => 'required|exists:subjects,id',
            'marks' => 'required|array',
            'marks.*' => 'required|integer|min:0|max:100',
        ]);
    
        foreach ($request->input('marks') as $studentId => $markValue) {
            Mark::updateOrCreate(
                [
                    'exam_id' => $request->input('exam_id'),
                    'class_id' => $request->input('class_id'),
                    'subject_id' => $request->input('subject_id'),
                    'school_id' => Auth::user()->school_id,
                    'student_id' => $studentId,
                ],
                ['marks' => $markValue]
            );
        }
    
        return redirect()->route('marks.index')->with('success', 'Marks updated successfully.');
    }
    
    
    public function marks(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $exams = Exam::where('school_id', $schoolId)->get();
        $classes = collect();
        $subjects = collect();
        $students = collect();
        $marks = collect();
        $subject_name = '';
        $class_name = '';
        $exam_name = '';
    
        $selectedExamId = $request->input('exam_id');
        $selectedClassId = $request->input('class_id');
        $selectedSubjectId = $request->input('subject_id');
    
        if ($selectedExamId) {
            $selectedExam = Exam::where('id', $selectedExamId)
                                ->where('school_id', $schoolId)
                                ->with('classes', 'subjects')
                                ->first();
    
            if ($selectedExam) {
                $classes = $selectedExam->classes;
                $subjects = $selectedExam->subjects;
                $exam_name = $selectedExam->name;
            }
        }
    
        if ($selectedExamId && $selectedSubjectId) {
            if ($selectedClassId) {
                $students = StudentInfo::where('class_id', $selectedClassId)->get();
                $class_name = $students->first()->class->name ?? '';
            }

            $marks = Mark::where('exam_id', $selectedExamId)
                         ->where('subject_id', $selectedSubjectId)
                         ->get()
                         ->keyBy('student_id');
        
            if ($selectedSubjectId) {
                $subject = Subject::find($selectedSubjectId);
                $subject_name = $subject->name ?? '';
            }
        }
    
        return view('school_admin.marks', compact(
            'classes', 
            'exams', 
            'subjects', 
            'students', 
            'marks', 
            'subject_name', 
            'class_name', 
            'exam_name'
        ));
    }

    public function tabulation(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $exams = Exam::where('school_id', $schoolId)->get();
        
        $classes = collect();
        $subjects = collect();
        $students = collect();
        $marks = collect();
        $class_name = '';
        $exam_name = '';
        
        $selectedExamId = $request->input('exam_id');
        $selectedClassId = $request->input('class_id');
        
        if ($selectedExamId) {
            $selectedExam = Exam::where('id', $selectedExamId)
                                ->where('school_id', $schoolId)
                                ->with('classes', 'subjects')
                                ->first();     
            if ($selectedExam) {
                $classes = $selectedExam->classes;
                $subjects = $selectedExam->subjects;
                $exam_name = $selectedExam->name;
            }
        }
        
        if ($selectedExamId && $selectedClassId) {
            $students = StudentInfo::where('class_id', $selectedClassId)->get();
            $class_name = $students->first()->class->name ?? '';
            $marks = Mark::where('exam_id', $selectedExamId)
                         ->whereIn('student_id', $students->pluck('id'))
                         ->get()
                         ->groupBy('student_id');

            foreach ($students as $student) {
                $studentMarks = $marks->get($student->id, collect());
                $totalMarks = $studentMarks->sum('marks');
                $subjectCount = $studentMarks->count();
                $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
                
                $student->average = round($average, 2);
            }
            $students = $students->sortByDesc('average')->values();
            $position = 1;
            foreach ($students as $student) {
                $student->position = $position++;
            }
        }

    
        
        return view('school_admin.tabulation', compact(
            'classes', 
            'exams', 
            'subjects', 
            'students', 
            'marks', 
            'class_name', 
            'exam_name'
        ));
    }
    
    
    
    
    




}
