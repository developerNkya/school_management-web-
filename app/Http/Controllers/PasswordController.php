<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    
    public function changePassword(Request $request)
    {
        return view('passwords.change_password');
    }

    public function alterPassword(Request $request)
    {
        $rules = [
            'password' => 'required',
            'repeated' => 'required|same:password',
        ];
    
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()) {
            $redirectRoute = $request->has('page') && !empty($request->page) ? $request->page : 'changePassword';
            return redirect()->route($redirectRoute)->withErrors($validator)->withInput();
        }
    
        $user_id = $request->has('user_id') ? $request->user_id : Auth::user()->id;
    
        User::where('id', $user_id)
            ->update([
                'password' => bcrypt($request->repeated)
            ]);
    
        $redirectRoute = $request->has('page') && !empty($request->page) ? $request->page : 'changePassword';
        return redirect()->route($redirectRoute)->with('message', 'Password changed successfully!');
    }
    
    
}
