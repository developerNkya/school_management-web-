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

        $rules = [
            'driver_id' => 'required|integer|exists:users,id',
            'student_ids' => 'required|array',
            'student_ids.*' => 'required',
            'attendance_date' => 'required|date',
            'stage' => 'required|string|in:onboarding,offloadSchool,onboardHome,offloadHome,endTrip',
        ];

        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();

        if ($validator->fails() && !$request->toJson) {
            return redirect()->route('user_login')->withErrors(['error' => $error]);
        } else if ($validator->fails() && $request->toJson) {
            return response()->json([
                'success' => false,
                'message' => $error
            ]);
        }

        foreach ($request->student_ids as $studentId) {
            $updator = StudentInfo::where('registration_no', $studentId)
                ->update([
                    'activity' => $request->stage,
                    'driver_id' => $request->driver_id,
                ]);
            if (!$updator) {
                return response()->json([
                    'success' => true,
                    'message' => 'Student Not found!..Please Scan Again',
                ]);
            }
        }

        if (!$request->has('driver_activity')) {
            Driver::where('user_id', $request->driver_id)
                ->update([
                    'activity' => $request->stage,
                ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Attendance Recorded',
        ]);
    }

    public function driverAttendance(Request $request)
    {
        
        if (!$request->has('driver_id')) {
            return view('bus_management.driver_attendance', [
                'drivers' => User::where('school_id', Auth::user()->school_id)
                                ->where('role_id', 5)
                                ->paginate(10)
            ]);
        }
    
        
        $activity = Driver::where('user_id', $request->driver_id)->value('activity');
        $activity_map = [
            'onboard' => 'Onboard Students from Home',
            'offloadSchool' => 'Offload Students at School',
            'onboardHome' => 'Onboard Students for Home Shift',
            'offloadHome' => 'Offload Students at Home',
            'endTrip' => 'Finished Trip',
            null => 'Not started Trip',
            '' => 'Not started Trip'
        ];
        $activity = $activity_map[$activity] ?? 'Unknown Activity';
    
        
        $query = StudentInfo::selectRaw(
                '*, CONCAT(first_name, " ", IFNULL(middle_name, ""), " ", last_name) AS full_name'
            )
            ->with('SchoolClass')
            ->where('school_id', $request->school_id)
            ->where('driver_id', $request->driver_id)
            ->when($activity === 'Not started Trip', function ($q) {
                $q->whereNull('activity')->orWhere('activity', 'onboarding');
            }, function ($q) use ($activity) {
                $q->where('activity', $activity);
            });
    
        $driver_attendance = $query->paginate(10);
        $total_students = StudentInfo::where('driver_id', $request->driver_id)
                                    ->where('school_id', $request->school_id)
                                    ->count();
    
        
        return $request->toJson
            ? response()->json([
                'success' => true,
                'driver_attendance' => $driver_attendance,
                'activity' => $activity,
                'total_students' => $total_students,
                'driver_name' => User::where('id', $request->driver_id)->value('name'),
                'date' => now()->format('Y-m-d'),
                'driver_id' => $request->driver_id
            ])
            : null;
    }
    



    public function filterDrivers($name)
    {

        $drivers = User::where('school_id', Auth::user()->school_id)
            ->where('role_id', 5)
            ->where(function ($query) use ($name) {
                $query->where('name', 'LIKE', "%{$name}%")
                    ->orWhere('email', 'LIKE', "%{$name}%")
                    ->orWhere('phone', 'LIKE', "%{$name}%");
            })
            ->paginate(10);

        return response()->json([$drivers]);
    }


    public function updateActivity(Request $request)
    {
        $checker = StudentInfo::where('driver_id', $request->driver_id)
            ->where('school_id', $request->school_id)
            ->where('activity', '!=', $request->current_activity)
            ->first();

        if ($checker) {
            return response()->json([
                'success' => false,
                'message' => 'Take attendace of all students first!'
            ]);
        }

        Driver::where('user_id', $request->driver_id)
            ->where('school_id', $request->school_id)
            ->update([
                'activity' => $request->next_activity,
            ]);

        if ($request->toJson) {
            return response()->json([
                'success' => true,
                'data' => 'Activity updated successfully'
            ]);
        }


    }




}
