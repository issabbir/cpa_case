<?php

namespace App\Http\Controllers\cms;

use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LawyerBillController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $searchInfo   = '';
        $editData   = '';

        $querys = "SELECT CPACMS.lawyer_master_info_list_bill FROM DUAL" ;
        $lawyerList = DB::select($querys);

        return view('cms.lawyerbillinfo.lawyerBillInfo', [
            'lawyerList' => $lawyerList,
            'editData' => $editData,
            'searchInfo' => $searchInfo]);
    }

    public function getCase($lawyer_id)
    {
        $querys = "SELECT CPACMS.lawyer_case_list ('".$lawyer_id."') FROM DUAL";
        $caseList = DB::select($querys);
        return $caseList;
    }

    public function datatable(Request $request)
    {
        $lawyer = $request->get("lawyer");
        $case_no = $request->get("case_no");
        $service_date_from = $request->get("service_date_from");
        $service_date_to = $request->get("service_date_to");

        $querys = "SELECT CPACMS.case_service_search_for_bill ('".$lawyer."','".$case_no."','".$service_date_from."','".$service_date_to."') FROM DUAL" ;
        $searchInfo = DB::select($querys);
dd($searchInfo);
        return datatables()->of($searchInfo)
            ->addColumn('action', function ($query) {
                return '<a class="editButton"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeLawyerBillInformation(Request $request)
    {
        $getReturn = $this->storeLawyerBillInfo($request);
        $result = ['code' => $getReturn['o_status_code'], 'msg' => $getReturn['o_status_message']];
        return $result;
    }

    public function storeLawyerBillInfo($request = [])
    {
        $bill_id = '';
        $bill_no = '';

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_bill_id" => $bill_id,
                "p_bill_no" => $bill_no,
                "p_bill_for_date" => $request->get("bill_for_date"),
                "p_bill_amount" => $request->get("amount"),
                "p_comments" => $request->get("comments"),
                "p_case_id" => $request->get("case_id"),
                "p_rate_chart_id" => $request->get("rate_chart_id"),
                "p_lawyer_id" => $request->get("lawyer_id"),
                "p_assignment_type_id" => $request->get("assignment_type_id"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];//dd($params);
            DB::executeProcedure("CPACMS.bill_master_info_creation", $params);

        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function lawyerBillSubmit()
    {
        $querys = "SELECT CPACMS.lawyer_master_info_list_bill FROM DUAL" ;
        $lawyerList = DB::select($querys);

        return view('cms.lawyerbillinfo.billSubmit', compact('lawyerList'));
    }

    public function billTable(Request $request)
    {
        $lawyer = $request->get("lawyer");
        $case_no = $request->get("case_no");
        $service_date_from = $request->get("service_date_from");
        $service_date_to = $request->get("service_date_to");
//return $service_date_from;
//        $querys = "SELECT CPACMS.lawyer_submit_grid_date ('".$lawyer."','".$service_date_from."','".$service_date_to."','".$case_no."') FROM DUAL" ;
        $querys = "SELECT lawyer_submit_grid_data ('".$lawyer."', TO_DATE ('".$service_date_from."', 'DD-MM-RRRR'), TO_DATE ('".$service_date_to."', 'DD-MM-RRRR'),'".$case_no."')FROM DUAL";
        $searchInfo = DB::select($querys);

        return view('cms.lawyerbillinfo.ajax_lawyer_table', compact('searchInfo'));
//        return datatables()->of($searchInfo)
//            ->addColumn('checkbox', function ($query) {
//                return '<input type="checkbox" name="record">';
//            })
//            ->addIndexColumn()
//            ->make(true);
    }

    public function billVoucherTable(Request $request){
        $lawyer = $request->get("lawyer");
        $case_no = $request->get("case_no");
        $service_date_from = $request->get("service_date_from");
        $service_date_to = $request->get("service_date_to");
//return $service_date_from;
//        $querys = "SELECT CPACMS.lawyer_submit_grid_date ('".$lawyer."','".$service_date_from."','".$service_date_to."','".$case_no."') FROM DUAL" ;
        $querys = "SELECT submitted_bill_voucher ('".$lawyer."', TO_DATE ('".$service_date_from."', 'DD-MM-RRRR'), TO_DATE ('".$service_date_to."', 'DD-MM-RRRR'),'".$case_no."')FROM DUAL";
        $searchInfo = DB::select($querys);

        return view('cms.lawyerbillinfo.ajax_voucher_table', compact('searchInfo'));
    }

    public function saveBill(Request $request)
    {

        $response = $this->lawyerBillSubmitCreation($request);
        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            session()->flash('message', $message);

            return redirect()->route('cms.lawyer-bill-info.lawyer-bill-submit')->withInput();
        }

        $bill_submit_id = $response['o_bill_submit_id'];
        $all_info = $request->all();

        $response = $this->billSubmitInfoCreation($bill_submit_id, $all_info);
        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            session()->flash('message', $message);

            return redirect()->route('cms.lawyer-bill-info.lawyer-bill-submit')->withInput();
        }
        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

//        return redirect()->route('cms.lawyer-bill-info.lawyer-bill-submit')->withInput();
        return redirect('/lawyer-bill-submit');
    }

    public function lawyerBillSubmitCreation($request = [])
    {
        $bill_submit_id = '';

        try {
            $o_bill_submit_id = sprintf("%4000s", "");
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_bill_submit_id" => $bill_submit_id,
                "p_bill_amount" => $request->get("total_bill"),
                "p_bill_approve_amount" => $request->get("total_approve"),
                "p_insert_by" => auth()->id(),
                "o_bill_submit_id" => &$o_bill_submit_id,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.lawyer_bill_submit_creation", $params);

        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function billSubmitInfoCreation($bill_submit_id, $all_info)
    {
        $params = '';
        foreach ($all_info['checkbox'] as $value){
            try {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_bill_id" => $all_info['bill_id'][$value],
                    "p_bill_submit_id" => $bill_submit_id,
                    "p_bill_approve_amount" => $all_info['approve_amount'][$value],
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message,
                ];
                DB::executeProcedure("CPACMS.bill_submit_info_creation", $params);

            } catch (\Exception $e) {
                $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
            }
        }
        return $params;
    }
}
