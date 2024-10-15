<?php

namespace App\Http\Controllers;

use App\Models\Finance;
use App\Models\StudentInfo;
use Auth;
use Illuminate\Http\Request;

class FinanceController extends Controller
{
    public function financePage(Request $request)
    {
        $finances = Finance::where('school_id',Auth::user()->school_id)->paginate(10);
        $total_students = StudentInfo::where('school_id',Auth::user()->school_id)->count();
        $next_payment = $total_students * 1000;
        return view('school_admin.finances',['finances'=>$finances,'next_payment'=>$next_payment]);
    }
}
