<?php

namespace App\Http\Controllers;

use App\Jobs\SendSms;
use App\Models\Exam;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\ResultSent;
use App\Models\SchoolClass;
use App\Models\StudentInfo;
use App\Models\Subject;
use Auth;
use DB;
use Illuminate\Http\Request;
use App\Constants;

class ResultController extends Controller
{
    public function sendResultsPage(Request $request)
    {
        $schoolId = Auth::user()->school_id;
        $exams = Exam::where('school_id', $schoolId)
            ->whereHas('resultSent', function ($query) use ($schoolId) {
                $query->where('school_id', $schoolId)
                    ->whereNotNull('sent_at');
            })
            ->with('classes')
            ->paginate(10);

        return view('school_admin.send_exams', ['exams' => $exams]);
    }

    public function unsentExams(Request $request)
    {
        $classes = SchoolClass::where('school_id', Auth::user()->school_id)->get();
        $exams = Exam::where('school_id', Auth::user()->school_id)
            ->get();
        return response()->json(['exams' => $exams, 'classes' => $classes]);
    }


    public function sendExams(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'class_id' => 'required|exists:classes,id',
        ]);
    
        $schoolId = Auth::user()->school_id;
        $selectedExamId = $validated['exam_id'];
        $selectedClassId = $validated['class_id'];
    
        $resultsSent = ResultSent::where('exam_id', $selectedExamId)
            ->where('class_id', $selectedClassId)
            ->where('school_id', $schoolId)
            ->exists();
    
        if ($resultsSent) {
            return redirect()->route('sendResultsPage')
                ->withErrors('Results have already been sent to this class for this exam!')
                ->withInput();
        }
    
        $selectedExam = Exam::where('id', $selectedExamId)
            ->where('school_id', $schoolId)
            ->with('classes', 'subjects')
            ->first();
    
        if (!$selectedExam) {
            return redirect()->route('sendResultsPage')->withErrors('Sorry, Exam not found!')->withInput();
        }
    
        $exam_name = $selectedExam->name;
    
        $students = StudentInfo::where('class_id', $selectedClassId)
            ->select('student_info.*', DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as full_name"))
            ->get();
    
        if ($students->isEmpty()) {
            return redirect()->route('sendResultsPage')->withErrors('Sorry, no students found for the selected class!')->withInput();
        }
    
        $marks = Mark::where('exam_id', $selectedExamId)
            ->whereIn('student_id', $students->pluck('id'))
            ->get()
            ->groupBy('student_id');
    
        if ($marks->isEmpty()) {
            return redirect()->route('sendResultsPage')->withErrors('Marks not found for this exam!')->withInput();
        }
    
        foreach ($students as $key => $student) {
            $studentMarks = $marks->get($student->id, collect());
            $totalMarks = $studentMarks->sum('marks');
            $subjectCount = $studentMarks->count();
            $average = $subjectCount > 0 ? $totalMarks / $subjectCount : 0;
    
            $student->total_marks = $totalMarks;
            $student->average = round($average, 2);
            $student->marks = $studentMarks->map(function ($mark) {
                return [
                    'subject_name' => $mark->subject->name,
                    'marks' => $mark->marks,
                ];
            });
    
            $subjectMarks = $student->marks->map(function ($mark) {
                return "{$mark['subject_name']} -> {$mark['marks']}";
            })->implode(', ');
    
            $formattedPhone = HelperController::formatPhoneNumber($student->parent_phone);
            if ($formattedPhone) {
                $sentMessage = "MATOKEO - $exam_name ni: $subjectMarks. Wastani: {$student->average}. Asante.";
                SendSms::dispatch($formattedPhone, $sentMessage)->delay(now()->addSeconds(6 * $key));
            }
        }
    
        ResultSent::insert([
            'exam_id' => $selectedExamId,
            'class_id' => $selectedClassId,
            'school_id' => $schoolId,
            'sent_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    
        return redirect()->route('sendResultsPage')->with('message', 'Exams sent to parents successfully!');
    }
    
    
    


}
