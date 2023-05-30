<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        return view('dashboard.index');
    }

    public function dataTableList(Request $request)
    {
        $date = $request->get("case_date");
        $caseDate = isset($date) ? date('d-m-Y', strtotime($date)) : '';
        if($caseDate==''){
            $date = date('d-m-Y', time());
            $caseDate = $date;
        }

        $role_key = json_decode(Auth::user()->roles->pluck('role_key'));
        if (in_array("MOBILE_COURT", $role_key)) {
            //$querys = "SELECT CPACMS.case_dashboard ('" . $caseDate . "','MOBILE_COURT') FROM DUAL";
            $querys = "select mci.CASE_NO as case_name, 'Mobile Court' as court_name, COMMENTS as last_update_speech from MOBILE_CASE_INFO mci" ;
        } else {
            $querys = "SELECT CPACMS.case_dashboard ('" . $caseDate . "','') FROM DUAL";
        }



        $searchData = DB::select($querys);

        return datatables()->of($searchData)->addIndexColumn()->make(true);
    }
}
