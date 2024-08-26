<?php

namespace App\Http\Controllers;

use App\Models\Suggestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class SuggestionController extends Controller
{
    public function addSuggestion(Request $request)
    {

        $rules = [
            'sender_id' => 'required',
            'school_id' => 'required',
            'message' => 'required',
        ];
    
        $validator = validator::make($request->all(), $rules);
        $error = $validator->errors()->first();
        if ($validator->fails() && !$request->toJson) {
             return redirect()->route('/')->withErrors(['error' => $error]);
        }else if($validator->fails() && $request->toJson){
            return response()->json([
                'success'=>false,
                'message'=>$error
            ]); 
        }

        try {
            $suggestion = new Suggestion();
            $suggestion->student_id = $request->sender_id;
            $suggestion->school_id = $request->school_id;
            $suggestion->suggestion = $request->message;
            $suggestion->save();

            return response()->json([
                'success'=>true,
                'message'=>'Suggestion added Successfully!'
            ]); 

        } catch (\Exception $e) {
            if (!$request->toJson) {
                return redirect()->route('/')->withErrors(['error' => $e]);
           }else if($request->toJson){
               return response()->json([
                   'success'=>false,
                   'message'=>$e
               ]); 
           }
        }
    }
}
