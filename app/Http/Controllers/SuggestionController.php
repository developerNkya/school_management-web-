<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\StudentInfo;


class SuggestionController extends Controller
{
    public function addSuggestion(Request $request)
    {
        if ($request->toJson) {
            $rules = [
                'type' => 'required|in:issue,suggestion',
                'message' => 'required|string',
                'sender_id' => 'required',
                'school_id' => 'required',

            ];
        } else {
            $rules = [
                'type' => 'required|in:issue,suggestion',
                'message' => 'required|string',
            ];
        }

        $validator = \Validator::make($request->all(), $rules);
        $error = $validator->errors()->first();

        if ($validator->fails() && !$request->toJson) {
            return redirect()->route('/')->withErrors(['error' => $error]);
        } else if ($validator->fails() && $request->toJson) {
            return response()->json([
                'success' => false,
                'message' => $error,
            ]);
        }


        try {

            $schoolId = $request->school_id ? $request->school_id : Auth::user()->school_id;
            $senderId = $request->sender_id ? $request->sender_id : StudentInfo::where('user_id', Auth::user()->id)
                                                                    ->value('id');
            $suggestion = new Suggestion();
            $suggestion->student_id = $senderId;
            $suggestion->school_id = $schoolId;
            $suggestion->suggestion = $request->message;
            $suggestion->suggestion_type = $request->type;
            $suggestion->save();

            if ($request->toJson) {
            return response()->json([
                'success' => true,
                'message' => 'Suggestion sent successfully!',
            ]);
            }
            return redirect()->route('suggestions')->with('message', 'Suggestion sent successfully!');

        } catch (\Exception $e) {
            if (!$request->toJson) {
                return redirect()->route('suggestions')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
            } else if ($request->toJson) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]);
            }
        }
    }

    public function deleteSuggestion(Request $request)
    {

        $validated = $request->validate([
            'id' => 'required',
        ]);

        try {
            $delete_Suggestion = Suggestion::where('id',$request->id)->delete();         
        return redirect()->back()->with('message', 'Suggestion deleted Successfully!');
        } catch (\Exception $e) {
           
        return redirect()->back()->withErrors(['error' => 'An error occurred: ' . $e->getMessage()])->withInput();
        }
        
    }

}
