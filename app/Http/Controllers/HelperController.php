<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AttendenceData;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamSubject;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\StudentInfo;
use App\Models\Subject;
use App\Models\Suggestion;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HelperController extends Controller
{
    public function classExistance(Request $request)
    {
        $classExists = SchoolClass::where('school_id', Auth::user()->school_id)->first();
        if (!$classExists == null) {
            return response()->json(['exists' => true]);
        } else {
            return response()->json(['exists' => false]);
        }
    }


    public function deleteById(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required',
            'table' => 'required'
        ]);

        $table = $request->table;
        $message = '';
        try {
            switch ($table) {
                case 'subject':
                    AttendenceData::where('class_id',$request->id)->delete();
                    ExamSubject::where('subject_id',$request->id)->delete();
                    Mark::where('subject_id',$request->id)->delete();
                    Subject::where('id',$request->id)->delete();
                    $message = 'subject';
                    break;

                case 'schoolClass':
                    SchoolClass::where('id', $request->id)->delete();
                    AttendenceData::where('class_id',$request->id)->delete();
                    Mark::where('class_id',$request->id)->delete();
                    $message ='Class';
                    break;
                
                case 'teacher':
                    Teacher::where('id',$request->id)->delete();
                    $message = 'teacher';
                    break;

                case 'student':
                    $user_id = StudentInfo::where('id',$request->id)->first();
                    StudentInfo::where('id',$request->id)->delete();
                    User::where('id',$user_id->user_id)->delete();
                    AttendenceData::where('student_id',$request->id)->delete();
                    Suggestion::where('student_id',$request->id)->delete();
                    $message = 'student';
                    break;

                case 'exam':
                    Mark::where('exam_id',$request->id)->delete();
                    ExamSubject::where('exam_id',$request->id)->delete();
                    ExamClass::where('exam_id',$request->id)->delete();
                    Exam::where('id',$request->id)->delete();
                    $message = 'exam';
                    break;

                case 'event':
                    Event::where('id',$request->id)->delete();
                    $message = 'event';
                    break;

                case 'assignment':
                    Assignment::where('id',$request->id)->delete();
                    $message = 'assignment';
                    break;

                case 'driver':
                    User::where('id',$request->id)->delete();
                    $message = 'driver';
                    break;

                default:
                    # code...
                    break;
            }

        return redirect()->back()->with('message', $message.' deleted Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }

    }
}
