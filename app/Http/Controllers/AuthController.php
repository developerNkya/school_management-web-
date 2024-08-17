<?php

namespace App\Http\Controllers;
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
        if ($validator->fails()) {
            return redirect()->route('user_login')->withErrors(['error' => $error]);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            switch ($user->role_id) {
                case 1:
                    return redirect('super_admin/');
                case 2:
                    return redirect('school_admin/');
                case 3:
                    return redirect('student/');
                default:
                    // Handle unknown roles
                    break;
            }
        } else {
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
