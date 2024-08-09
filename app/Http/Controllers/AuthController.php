<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        $rules = [
            'email' => 'required',
            'password' => 'required',
        ];

        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $error
            ]);
        }

        $verifier = User::where('email', $request->email)
            ->where('password', $request->password)
            ->first();


        if (!$verifier) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry...Incorrect Email or Password!',
            ]);
        }

        //check role:
        switch ($verifier->role_id) {
            case 1:
                return redirect('super_admin/');
                break;

            case 2:
                return redirect('school_admin/');
                break;

            default:
                # code...
                break;
        }
    }
}
