<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusManagementController extends Controller
{
    public function allDrivers(Request $request)
    {
        $drivers = User::where('school_id', Auth::user()->school_id)
                         ->where('role_id',5)
                         ->paginate(10);

        return view('bus_management.all_drivers',['drivers' => $drivers]);
    }

    public function addDriver(Request $request)
    {

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => 'required|email|max:255',
        ]);

        $fullName = sprintf('%s %s %s', $request->first_name, $request->second_name,$request->last_name);

        if (User::where('email', '=', $request->email)->exists()) {
            return redirect()->route('allDriversPage')->withErrors('Sorry,Email.. already Exists!')->withInput();
         }

        $user = new User();
        $user->name = $fullName;
        $user->email = $request->email;
        $user->gender = $request->gender;
        $user->phone = $request->phone_number;
        $user->location = $request->nationality;
        $user->password = bcrypt('driver');
        $user->role_id = 5;
        $user->isActive = 1;
        $user->school_id = Auth::user()->school_id;
        $user->save();

        return redirect()->route('allDriversPage')->with('message', 'Driver added successfully!');

    }
}
