<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function organizeEvent(Request $request)
    {

        $school_id = $request->toJson ? $request->school_id : Auth::user()->school_id;
        $eventsQuery = Event::where('school_id', Auth::user()->school_id);
        $events = $eventsQuery->paginate(10);
        $total_events = $eventsQuery->count();

        return $request->toJson ? response()->json([
            'success' => true,
            'data' => [
                'events' => $events,
                'total_events'=>$total_events
            ],
        ]) : view('school_admin.organizeEvent', compact('events','total_events'));


    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'cost' => 'required|numeric|min:0',
            'description' => 'required|string',
        ]);

        $total_events = Event::where('school_id', Auth::user()->school_id)->count();
        if ($total_events >= 1) {
            return redirect()->back()->withErrors(['error' => 'Events space full! delete some events to proceed'])->withInput();
        }

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
