<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 2/11/20
 * Time: 11:14 AM
 */

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $querys = "SELECT case_no, case_date, case_description, next_date
        FROM case_master_info
        WHERE TRUNC (next_date) = TRUNC (SYSDATE + 2)";
//        $querys = "SELECT case_no, case_date, case_description,next_date
//        FROM case_master_info
//        WHERE TRUNC (next_date) = TRUNC (TO_DATE('29-DEC-20','DD-MON-YY') + 2)";
        $cases = DB::select($querys);
//        dd($cases);
        return view('dashboard.index', compact('cases'));
    }
}
