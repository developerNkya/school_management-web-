<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\Attendence;
use App\Models\AttendenceData;
use App\Models\Driver;
use App\Models\Event;
use App\Models\Exam;
use App\Models\ExamClass;
use App\Models\ExamSubject;
use App\Models\Grade;
use App\Models\Mark;
use App\Models\SchoolClass;
use App\Models\StudentInfo;
use App\Models\Subject;
use App\Models\Suggestion;
use App\Models\Teacher;
use App\Models\User;
use File;
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
                    AttendenceData::where('class_id', $request->id)->delete();
                    ExamSubject::where('subject_id', $request->id)->delete();
                    Mark::where('subject_id', $request->id)->delete();
                    Subject::where('id', $request->id)->delete();
                    $message = 'subject';
                    break;

                case 'schoolClass':
                    SchoolClass::where('id', $request->id)->delete();
                    AttendenceData::where('class_id', $request->id)->delete();
                    Mark::where('class_id', $request->id)->delete();
                    $message = 'Class';
                    break;

                case 'teacher':
                    Teacher::where('id', $request->id)->delete();
                    $message = 'teacher';
                    break;

                case 'student':
                    Mark::where('student_id', $request->id)->delete();
                    AttendenceData::where('student_id', $request->id)->delete();
                    Suggestion::where('student_id', $request->id)->delete();
                    $user_id = StudentInfo::where('id', $request->id)->first();
                    StudentInfo::where('id', $request->id)->delete();
                    User::where('id', $user_id->user_id)->delete();
                    $message = 'student';
                    break;

                case 'exam':
                    Mark::where('exam_id', $request->id)->delete();
                    ExamSubject::where('exam_id', $request->id)->delete();
                    ExamClass::where('exam_id', $request->id)->delete();
                    Exam::where('id', $request->id)->delete();
                    $message = 'exam';
                    break;

                case 'event':
                    Event::where('id', $request->id)->delete();
                    $message = 'event';
                    break;

                case 'assignment':
                    $assignment = Assignment::where('id', $request->id)->first();

                    if ($assignment && $assignment->file_path) {
                        $file_path = storage_path('app/public/' . str_replace('/', DIRECTORY_SEPARATOR, $assignment->file_path));
                        if (file_exists($file_path)) {
                            unlink($file_path);
                            $assignment->delete();
                        }
                    }
                    $message = 'assignment';
                    break;

                case 'driver':
                    User::where('id', $request->id)->delete();
                    $message = 'driver';
                    break;

                case 'attendance':
                    AttendenceData::where('attendence_id', $request->id)->delete();
                    Attendence::where('id', $request->id)->delete();
                    $message = 'attendance';
                    break;

                case 'grade':
                    Grade::where('id', $request->id)->delete();
                    $message = 'grade';
                    break;

                default:
                    # code...
                    break;
            }

            return redirect()->back()->with('message', $message . ' deleted Successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }

    }
    public static function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        if (strlen($phoneNumber) === 10 && $phoneNumber[0] === '0') {
            $phoneNumber = '255' . substr($phoneNumber, 1);
        }
        return (strlen($phoneNumber) === 12 && substr($phoneNumber, 0, 3) === '255') ? $phoneNumber : null;
    }


    public static function totalUsers($type, $school_id)
    {
        switch ($type) {
            case 'students':
                $total = StudentInfo::where('school_id', $school_id)->count();
                break;

            default:
                # code...
                break;
        }

        return $total;
    }

    public static function emailAssigner($user_role)
    {
        $user = Auth::user()->load('School');
        $school_initial = strtolower($user->school->initial);
        $counter = '';
        $role_var = null;
    
        
        function email_splitter($row, $role)
        {
            if ($row != '') {
                
                $split_email = explode("@", $row)[0];
    
                
                $role_initial = substr($split_email, 0, 1);
                $numeric_part = substr($split_email, 1);
    
                
                if ($role_initial === $role) {
                    $counter = (int)$numeric_part + 1; 
                } else {
                    $counter = 1; 
                }
                return $counter;
            } else {
                return 1; 
            }
        }
    
        switch ($user_role) {
            case 4: 
                $row = Teacher::where('school_id', $user->school_id)
                    ->orderBy('created_at', 'desc')->first();
                $role_var = 't'; 
                $counter = email_splitter($row ? $row->email : '', $role_var);
                break;
            case 3: 
                $row = StudentInfo::where('school_id', $user->school_id)
                    ->orderBy('created_at', 'desc')->first();
                $counter = email_splitter($row ? $row->parent_email : '', null); 
                break;
            case 5: 
                $row = Driver::where('school_id', $user->school_id)
                    ->orderBy('created_at', 'desc')->first();
                $role_var = 'd'; 
                $counter = email_splitter($row ? $row->email : '', $role_var);
                break;
    
            default:
                break;
        }
    
        
        $counter = str_pad($counter, 4, '0', STR_PAD_LEFT);
    
        
        $assigned_email = $user_role != 3 ? "{$role_var}{$counter}@{$school_initial}" : "{$counter}@{$school_initial}";
        return $assigned_email;
    }
    

}
