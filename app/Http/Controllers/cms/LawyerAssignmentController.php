<?php

namespace App\Http\Controllers\cms;

use App\Entities\Cms\CaseMasterInfo;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LawyerAssignmentController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $querys = "SELECT CPACMS.court_list ('') FROM DUAL" ;
        $courtList = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL" ;
        $caseStatusList = DB::select($querys);

        $querys = "SELECT CPACMS.case_information_search_list ('') FROM DUAL" ;
        $caseData = DB::select($querys);//dd($caseData);

        return view('cms.lawyerassignment.lawyerAssignment', [
            'caseData' => $caseData,
            'caseStatusList' => $caseStatusList,
            'courtList' => $courtList
        ]);
    }

    public function datatable(Request $request)
    {
        $status = $request->get("status");
        $court = $request->get("court");
        $case_no = $request->get("case_no");

        $querys = "SELECT CPACMS.lawyer_assignment_search_grid ('".$case_no."','".$status."','".$court."') FROM DUAL" ;
        $searchInfo = DB::select($querys);

        return datatables()->of($searchInfo)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.lawyer-assignment.lawyer-assignment-edit', [$query->case_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function edit($id = NULL)
    {
        if ($id)
        {
            $caseData = CaseMasterInfo::find($id)->toArray();
            $querys = "SELECT CPACMS.lawyer_assignment_grid ('".$id."') FROM DUAL" ;
            $gridData = DB::select($querys);
        }

        $querys = "SELECT CPACMS.lawyer_master_info_list ('".$id."') FROM DUAL" ;
        $lawyerList = DB::select($querys);

        return view('cms.lawyerassignment.lawyerAssignmentDetail', [
            'caseData' => $caseData,
            'gridData' => $gridData,
            'lawyerList' => $lawyerList]);
    }

    public function storeLawyerAssignData(Request $request)
    {
        $getReturn = $this->storeLawyerAssignInfo($request);

        if($getReturn['o_mobile_no'])
        {
//            $sendSms = $this->sendSMS($getReturn['o_mobile_no'], $getReturn['o_message_text'], $getReturn['o_trace_code'], $request);
            $sendSms = null;
            if($sendSms == '1')
            {
                $res = ['o_status_code' => '1', 'o_status_message' => $getReturn['o_status_message'].' A message sent to assigned lawyer mobile.'];
                $flashMessageContent = $this->flashMessageManager->getMessage($res);
                return redirect('/lawyer-assignment/' . $request->get("case_id"))->with($flashMessageContent['class'], $flashMessageContent['message']);
            }
            else
            {
                $res = ['o_status_code' => '1', 'o_status_message' => $getReturn['o_status_message'].' Message sending Failed.'];
                $flashMessageContent = $this->flashMessageManager->getMessage($res);
                return redirect('/lawyer-assignment/' . $request->get("case_id"))->with($flashMessageContent['class'], $flashMessageContent['message']);
            }
        }
        else
        {
            $res = ['o_status_code' => '1', 'o_status_message' => $getReturn['o_status_message'].' No Mobile Number Found to Send SMS.'];
            $flashMessageContent = $this->flashMessageManager->getMessage($res);
            return redirect('/lawyer-assignment/' . $request->get("case_id"))->with($flashMessageContent['class'], $flashMessageContent['message']);
        }
    }

    public function storeLawyerAssignInfo($request = [])
    {
        $assignment_id = '';
        if($request->get("assignment_id"))
        {
            $assignment_id = $request->get("assignment_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $mobile_no = sprintf("%4000s", "");
            $message_text = sprintf("%4000s", "");
            $trace_code = sprintf("%4000s", "");
            $params = [
                "p_assignment_id" => $assignment_id,
                "p_description" => $request->get("case_description"),
                "p_case_id" => $request->get("case_id"),
                "p_case_status_id" => $request->get("case_status_id"),
                "p_lawyer_id" => $request->get("lawyer_id"),
                "p_insert_by" => auth()->id(),
                "o_mobile_no" => &$mobile_no,
                "o_message_text" => &$message_text,
                "o_trace_code" => &$trace_code,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_lawyer_assign_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function sendSMS($sender_no, $msg_bdy, $trace_code, $request)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://192.168.78.10:5123/api/v1/client/sms-request",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => array('msisdn' => $sender_no,'msg' => $msg_bdy,'service' => '3','trace_code' => $trace_code),
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer rZK0hN5unNzkowe4FrRcmOS7fhvWBrSnjqAm3OXnkjQ7aMpGLY51mpxJ7avnhCj9fyTbFK0AidkKitcq"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response, true);
        if($json['code'])
        {
            if($json['code']=="000")
            {
                $notification_id = '';
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_notification_id" => $notification_id,
                    "p_case_id" => $request->get("case_id"),
                    "p_lawyer_id" => $request->get("lawyer_id"),
                    "p_notification_subject" => '',
                    "p_message" => $msg_bdy,
                    "p_notify_from" => 'CPA',
                    "p_notify_to" => $sender_no,
                    "p_cc_to" => '',
                    "p_bcc_to" => '',
                    "p_auto_send_yn" => 'Y',
                    "p_notify_for" => '',
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message,
                ];
                DB::executeProcedure("CPACMS.case_lawyer_notification", $params);
                $result = '1';
            }
            else
            {
                $result = '0';
            }
        }
        else
        {
            $result = '0';
        }

        return $result;
    }
}
