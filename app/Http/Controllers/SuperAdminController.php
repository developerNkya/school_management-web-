<?php

namespace App\Http\Controllers;

use App\Models\Finance;
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
            'school_initial' => 'required',
            'school_location' => 'required',
            'owner_name' => 'required',
            'owner_phone_no' =>['required', 'string', 'regex:/^0\d{9}$/'],
        ];

        $customMessages = [
            'owner_phone_no' => 'Enter a 10-digit number starting with 0 for the owner\'s phone.',
        ];

        $validator = validator::make($request->all(), $rules,$customMessages);
        $error = $validator->errors()->first();
        if ($validator->fails()) {
            return redirect()->route('add_school_page')->withErrors(['error' => $error]);
        }

        try {
            $user = new User();
            $user->name = strtolower($request->owner_name);
            $user->email = 'owner@'.$request->school_initial;
            $user->phone = $request->owner_phone_no;
            $user->location = $request->school_location;
            $user->password = bcrypt( 'owner@'.$request->school_initial);
            $user->role_id = 2;
            $user->isActive = 1;
            $user->save();

            $owner_id = $user->id;

            $school = new School();
            $school->school_name = $request->school_name;
            $school->location = $request->school_location;
            $school->owner_id = $owner_id;
            $school->initial =  $request->school_initial;
            $school->save();

            $school_id = $school->id;
            User::where('id', $owner_id)
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
        $schools = School::with('owner')->paginate(10);
        return view('super_admin.school_page',compact('schools'));
    }

    public function rolesPage(Request $request)
    {
        return view('super_admin.roles_page');
    }

    public function activationPage(Request $request)
    {

        $users = User::with('role')->paginate(10);
        return view('super_admin.activationPage', compact('users'));
    }

    public function payments(Request $request)
    {
        $schools =   School::select('school_name','id')->get();
        return view('super_admin.payments',['schools'=>$schools]);
    }
    
    public function updatePayment(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'last_payment' => 'required|numeric',
            'last_payment_date' => 'required|date',
            'pending_balance' => 'required|numeric',
            'next_payment_date' => 'required|date',
            'next_payment_amount' => 'required|numeric',
        ]);

        $paymentData = [
            'last_payment' => $validated['last_payment'],
            'last_payment_date' => $validated['last_payment_date'],
            'pending_balance' => $validated['pending_balance'],
            'next_payment_date' => $validated['next_payment_date'],
            'next_payment_amount' => $validated['next_payment_amount'],
            'school_id' => $validated['school_id'],
        ];

        $payment = Finance::updateOrCreate(
            ['school_id' => $validated['school_id']],
            $paymentData
        );

        return redirect()->back()->with('message', 'Payment information updated successfully!');
    }


    public function filterUsers($name)
    {
        $users = User::where('name', 'LIKE', "%{$name}%")
            // ->orWhere('first_name', 'LIKE', "%{$name}%")
            // ->orWhere('last_name', 'LIKE', "%{$name}%")
            ->with('role')
            ->paginate(10);

        return response()->json($users);
    }

    public function alterUserStatus($id)
    {

        $user = User::findOrFail($id);
        $user->isActive = !$user->isActive;
        $user->save();
         return response()->json(['status' => $user->isActive ? 'active' : 'inactive']);
    }


}
