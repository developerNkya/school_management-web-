<?php

namespace App\Http\Controllers;
use App\Models\Role;
use App\Models\StudentInfo;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Session;

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
          
            if(!$user->isActive && $request->toJson){
                return response()->json([
                    'success'=>false,
                    'message'=>'Sorry...User is not Activated! Please contact Admin for more info'
                ]);  
            }else if (!$user->isActive && !$request->toJson) {
                return redirect()->route('user_login')->withErrors(['error' => 'Sorry...User is not Activated! Please contact Admin for more info']);
            }

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

            Session::put('user_name',$user->name);
            Session::put('role_id',$user->role_id);
            switch ($user->role_id) {
                case 1:
                    return redirect('super_admin/home');
                case 2:
                    return redirect('school_admin/home');
                case 3:
                    return redirect('student/home');
                case 4:
                    return redirect('school_admin/home');
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

        $role_length = Role::get()->count();

        if($role_length<=0){
            $keys =["SUPER_ADMIN","SCHOOL_ADMIN","STUDENT","TEACHER","DRIVER"];
            $counter = 1;
            foreach ($keys as $key) {
               Role::create(['id' =>$counter,'name' => $key]);
               $counter = $counter +1;
            }
        }
        
           $user_checker = User::where('email','admin@admin.com')->first();
           if ($user_checker === null) {
            $user_checker = User::create([
                'name' => 'admin',
                 'email'=>'admin@admin.com',
                 'phone' => '+255 620 416 606',
                 'location' =>'Dar es salaam',
                 'password' => bcrypt('admin'),
                 'role_id' => 1,
                 'isActive' =>1
                ]);         
           }else{
            return json_encode('success!');
           }
    }
    
}
