<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use Auth;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function financePage(Request $request)
    {
        $finances = Finance::where('school_id',Auth::user()->school_id)->paginate(10);
        return view('school_admin.finances',['finances'=>$finances]);
    }
}
