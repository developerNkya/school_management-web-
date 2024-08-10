<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SuperAdminController extends Controller
{
    public function index(Request $request)
    {
        return view('super_admin.index');
    }

    public function addSchool(Request $request)
    {
        $rules = [
            'school_name' => 'required',
            'school_location' => 'required',
            'owner_name' => 'required',
            'owner_phone_no' => 'required',
            'owner_email' => 'required',
            'new_password' => 'required',
            'repeat_password' => 'required',
        ];

        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return redirect()->route('add_school_page')->withErrors(['error' => $error]);
        }

        if ($request->new_password != $request->repeat_password) {
            return redirect()->route('add_school_page')->withErrors(['error' => 'Passwords should be same']);
        }

        try{
        $user = new User();
        $user->name = $request->owner_name;
        $user->email = $request->owner_email;
        $user->phone = $request->owner_phone_no;
        $user->location = $request->school_location;
        $user->password = $request->new_password;
        $user->role_id = 2;
        $user->save();

        $owner_id = $user->id;

        $school = new School();
        $school->school_name = $request->school_name;
        $school->location = $request->school_location;
        $school->owner_id = $owner_id;
        $school->save();

        $school_id = $school->id;
        User::where('id',$owner_id)
                ->update([
                    'school_id' => $school_id
                ]);
                    
        return redirect()->route('add_school_page')->with('message', 'School added successfully!');

    } catch (\Exception $e) {
        return redirect()->route('add_school_page')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
    }
    }

    public function schoolPage(Request $request)
    {
        return view('super_admin.school_page');
    }

    public function rolesPage(Request $request)
    {
        return view('super_admin.roles_page');
    }
}
