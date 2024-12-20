<?php

namespace App\Http\Controllers;

use App\Models\AppVersion;
use App\Models\ContactMail;
use Illuminate\Http\Request;

class StarterController extends Controller
{
    public function index(Request $request)
    {
        return view('starter.home.index');
    }

    public function appVersion(Request $request)
    {
        if (AppVersion::where('current_version', '=',$request->current_version)->exists()) {
            return response()->json([
                'success'=>true,
                'message'=>'App is up to date'
            ]);  
         }else{
            return response()->json([
                'success'=>false,
                'message'=>'Sorry...app is not up to date'
            ]);  
         }
    }

    public function login(Request $request)
    {
        return view('auth.login');
    }

    public function contactUs(Request $request)
    {
        return view('starter.contacts');
    }

    public function contactMessage(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'from' => 'required|string|max:255',
            'message' => 'required|string',
        ]);
    
        try {
            ContactMail::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone_no' => $validated['phone'],
                'from' => $validated['from'],
                'message' => $validated['message'],
            ]);

            return redirect()->back()->with('message', 'Your message has been sent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Sorry..something went');
        }
      
    }

    public function aboutUs(Request $request)
    {
        return view('starter.about_us');
    }

    public function Downloads(Request $request)
    {
        return view('starter.downloads');
    }
}
