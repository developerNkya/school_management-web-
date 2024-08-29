<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function organizeEvent(Request $request)
    {
        $events = Event::get();
        
        return view('school_admin.organizeEvent',compact('events'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'cost' => 'required|numeric',
            'description' => 'required|string',
        ]);

        Event::create([
            'name' => $validated['event_name'],
            'event_date' => $validated['event_date'],
            'cost' => $validated['cost'],
            'description' => $validated['description'],
            'school_id' => Auth::user()->school_id,
        ]);

        return redirect()->route('organizeEvent')->with('message', 'Event added successfully!');
    }
}
