<?php

namespace App\Http\Controllers;
use App\Models\StudentInfo;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];


        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails() && !$request->toJson) {
            return redirect()->route('user_login')->withErrors(['error' => $error]);
        }else if($validator->fails() && $request->toJson){
            return response()->json([
                'success'=>false,
                'message'=>$error
            ]); 
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user()->load('School');
            if ($request->toJson) {
                if ($user->role_id == 3) {
                    $student_info = StudentInfo::with('SchoolClass', 'section')->where('user_id', Auth::user()->id)
                                    ->first();

                    return response()->json([
                        'success'=>true,
                        'data'=>[
                          'user' =>$user,
                          'student_info' => $student_info
                        ],
                    ]); 
                }
                return response()->json([
                    'success'=>true,
                    'data'=>$user
                ]); 
            }

            switch ($user->role_id) {
                case 1:
                    return redirect('super_admin/home');
                case 2:
                    return redirect('school_admin/home');
                case 3:
                    return redirect('student/home');
                default:
                    // Handle unknown roles
                    break;
            }
        } else {

            if ($request->toJson) {
                return response()->json([
                    'success'=>false,
                    'message'=>'Sorry...Incorrect Email or Password!'
                ]); 
            }

            return redirect()->route('user_login')->withErrors(['error' => 'Sorry...Incorrect Email or Password!']);
        }
    }


    public function  initialUser(Request $request)
    {

        $user = new User();
        $user->name = 'admin';
        $user->email = 'admin@admin.com';
        $user->phone = '+255 620 416 606';
        $user->location = 'Dar es salaam';
        $user->password = bcrypt('admin');
        $user->role_id = 1;
        $user->save();

        return json_encode('success!');

    }
    
}
