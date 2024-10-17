<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\StudentInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BusManagementController extends Controller
{
    public function allDrivers(Request $request)
    {
        $drivers = User::where('school_id', Auth::user()->school_id)
            ->where('role_id', 5)
            ->paginate(10);

        return view('bus_management.all_drivers', ['drivers' => $drivers]);
    }

    public function addDriver(Request $request)
    {

        $rules = [
            'first_name' => 'required|string|max:255',
            'second_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female',
            'nationality' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone_number' => ['required', 'string', 'regex:/^0\d{9}$/']
        ];

        $customMessages = [
            'phone_number.regex' => 'Enter a 10-digit number starting with 0 for the teacher\'s phone.',
        ];
        $validator = Validator::make($request->all(), $rules, $customMessages);

        if ($validator->fails()) {
            return redirect()->route('all_drivers_page')->withErrors($validator)->withInput();
        }

        $user_email = HelperController::emailAssigner(5);
        $fullName = sprintf('%s %s %s', $request->first_name, $request->second_name, $request->last_name);


        $user = new User();
        $user->name = $fullName;
        $user->email = $user_email;
        $user->gender = $request->gender;
        $user->phone = $request->phone_number;
        $user->location = $request->nationality;
        $user->password = bcrypt('driver');
        $user->role_id = 5;
        $user->isActive = 1;
        $user->school_id = Auth::user()->school_id;
        $user->save();

        $driver_id = $user->id;

        $driver = new Driver();
        $driver->user_id = $driver_id;
        $driver->email = $user_email;
        $driver->school_id = Auth::user()->school_id;
        $driver->save();

        return redirect()->route('allDriversPage')->with('message', 'Driver added successfully!');

    }

    public function dailyBusAttendance(Request $request)
    {
        $validated = $request->validate([
            'driver_id' => 'required|integer|exists:users,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'integer|exists:student_info,registration_no',
            'attendance_date' => 'required|date',
            'stage' => 'required|string|in:onboarding,offloadSchool,onboardHome,offloadHome',
        ]);
        
        $attendanceData = [
            'driver_id' => $validated['driver_id'],
            'student_ids' => json_encode($validated['student_ids']),
            'attendance_date' => $validated['attendance_date'],
            'stage' => $validated['stage'],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    
        foreach ($validated['student_ids'] as $studentId) {
            StudentInfo::where('registration_no', $studentId)
                ->update([
                    'location' => $validated['stage'],
                    'driver_id' => $validated['driver_id'],
                ]);
        }
    

        return response()->json([
            'success' => true,
            'message' => 'Attendance Recorded',
        ]);
    }
    
    
    public function driverAttendance(Request $request)
    {
        $driver_attendance = StudentInfo::with('SchoolClass')
            ->where('school_id', $request->school_id)
            ->where('driver_id', $request->driver_id)
            ->paginate(10);

        if($request->toJson){
            return response()->json([
                'success' => true,
                'driver_attendance' => $driver_attendance
            ]);
        }

        // return view('bus_management.all_drivers', ['drivers' => $drivers]);
    }

}
