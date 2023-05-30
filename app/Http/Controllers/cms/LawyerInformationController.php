<?php

namespace App\Http\Controllers\cms;

use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoDivision;
use App\Entities\Admin\LGeoThana;
use App\Entities\Cms\Bank;
use App\Entities\Cms\Branch;
use App\Entities\Cms\CaseCategory;
use App\Entities\Cms\CourtMasterInfo;
use App\Entities\Cms\LawyerMasterInfo;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;

class LawyerInformationController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $editData   = '';
        $districtList = '';
        $branchList = '';
        $userCourtData = '';
        $userCaseData = [];
        $thanaList = '';

        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        //$bankList = Bank::orderBy('bank_id', 'asc')->get();
        $bankList = DB::table('L_BANKS')->get();

        $querys1 = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys1);

        $courtList = CourtMasterInfo::orderBy('court_id', 'asc')->where('ACTIVE_YN', '=', 'Y')->get();
        $caseCategoryList = CaseCategory::orderBy('category_id', 'asc')->get();

        return view('cms.lawyerinformation.lawyerInformation', [
            'editData' => $editData,
            'divisionList' => $divisionList,
            'districtList' => $districtList,
            'bankList' => $bankList,
            'branchList' => $branchList,
            'courtList' => $courtList,
            'thanaList' => $thanaList,
            'caseCategoryList' => $caseCategoryList,
            'userCourtData' => $userCourtData,
            'userCaseData' => $userCaseData,
            'active_yn' => $active_yn]);
    }

    public function edit(Request $request, $id)
    {
        $editData = LawyerMasterInfo::find($id)->toArray();

        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        $bankList = DB::table('L_BANKS')->get();
        $courtList = CourtMasterInfo::orderBy('court_id', 'asc')->where('ACTIVE_YN', '=', 'Y')->get();
        $caseCategoryList = CaseCategory::orderBy('category_id', 'asc')->get();
        $querys1 = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys1);

        $querys = "SELECT CPACMS.lawyer_court_mapping_grid_list('".$id."') from dual" ;
        $userCourtData = DB::select(DB::raw($querys));

        $querys1 = "SELECT CPACMS.lawyer_case_mapping_grid_list('".$id."') from dual" ;
        $userCaseData = DB::select(DB::raw($querys1));

        $bank_id =  $editData['bank_id'];
        //$branchList = Branch::where('branch_id', '=', $branch_id)->get();
        $branchList = DB::table('L_BRANCH')->where('bank_id', '=', $bank_id)->get();

        $division_id =  $editData['division_id'];
        $districtList = LGeoDistrict::where('geo_division_id', '=', $division_id)->get();

        $district_id =  $editData['district_id'];
        $thanaList = LGeoThana::where('geo_district_id', '=', $district_id)->get();

        return view('cms.lawyerinformation.lawyerInformation', [
            'editData' => $editData,
            'divisionList' => $divisionList,
            'districtList' => $districtList,
            'bankList' => $bankList,
            'branchList' => $branchList,
            'courtList' => $courtList,
            'thanaList' => $thanaList,
            'caseCategoryList' => $caseCategoryList,
            'userCourtData' => $userCourtData,
            'userCaseData' => $userCaseData,
            'active_yn' => $active_yn]);
    }

    public function storeLawyerInformation(Request $request)
    {
        $getReturn = $this->storeLawyerInfo($request);
        if($getReturn['o_lawyer_id'])
        {
            $getReturnDtl = $this->storeLawyerCourtInfoDtl($request, $getReturn['o_lawyer_id']);
            $getReturnDtludt = $this->storeLawyerCourtInfoDtlUpdt($request, $getReturn['o_lawyer_id']);
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($getReturn);
        return redirect('/lawyer-info')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function updateLawyerInformation(Request $request)
    {
        $getReturn = $this->storeLawyerInfo($request);
        if($request->get('chk_court'))
        {
            $getReturnDtl = $this->storeLawyerCourtInfoDtl($request, $request->get('lawyer_id'));
            $getReturnDtludt = $this->storeLawyerCourtInfoDtlUpdt($request, $request->get('lawyer_id'));
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($getReturn);
        return redirect('/lawyer-info')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function lawyerInfoDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.lawyer_master_info_grid_list FROM DUAL" ;
        $lawyerInformation = DB::select($querys);

        return datatables()->of($lawyerInformation)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.lawyer-info.lawyer-info-edit', $query->lawyer_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeLawyerInfo($request = [])
    {
        $lawyer_id = '';
        if($request->get("lawyer_id"))
        {
            $lawyer_id = $request->get("lawyer_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $lawyer_id_get = sprintf("%4000s", "");
            $params1 = [
                "p_lawyer_id" => $lawyer_id,
                "p_lawyer_name" => $request->get("lawyer_name"),
                "p_lawyer_name_bng" => $request->get("lawyer_name_bng"),
                "p_present_address" => $request->get("lawyer_present_address"),
                "p_permanent_address" => $request->get("lawyer_permanent_address"),
                "p_chamber_address" => $request->get("lawyer_chamber_address"),
                "p_district_id" => $request->get("district"),
                "p_division_id" => $request->get("division"),
                "p_thana_id" => $request->get("thana"),
                "p_contact_no" => $request->get("mobile_no"),
                "p_license_no" => $request->get("licence_no"),
                "p_enlistment_no" => $request->get("enlistment_no"),
                "p_enlistment_date" => $request->get("enlistment_date"),
                "p_expiry_date" => $request->get("expired_on"),
                "p_bank_acc_no" => $request->get("bank_ac_no"),
                "p_bank_acc_name" => $request->get("bank_ac_name"),
                "p_active_yn" => $request->get("active_yn"),
                "p_branch_id" => $request->get("branch"),
                "p_bank_id" => $request->get("bank"),
                "p_insert_by" => auth()->id(),
                "o_lawyer_id" => &$lawyer_id_get,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];

            DB::executeProcedure("CPACMS.lawyer_master_info_creation", $params1);

//            if($params1['o_status_code'] == 1)
//            {
//                $subject = 'Lawyer Enlisted';
//                Mail::to($params1[''])->send(new SendMail($subject, $params1['p_lawyer_name']));
//            }
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params1;

    }

    public function storeLawyerCourtInfoDtl($request, $lawyer_id)
    {
        $courtList = CourtMasterInfo::orderBy('court_id', 'asc')->get();
        if (!empty($courtList))
        {
            foreach ($courtList as $court)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_lawyer_id" => $lawyer_id,
                    "p_court_id" => $court->court_id,
                    "p_active_yn" => "N",
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message,
                ];
                DB::executeProcedure("CPACMS.lawyer_court_mapping_creation", $params);
            }
        }
    }

    public function storeLawyerCourtInfoDtlUpdt($request, $lawyer_id)
    {
        if($request->get("chk_court"))
        {
            for ($i = 0, $l = count($request->get('chk_court')); $i < $l; ++$i)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_lawyer_id" => $lawyer_id,
                    "p_court_id" => $request->get('chk_court')[$i],
                    "p_active_yn" => "Y",
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message,
                ];
                DB::executeProcedure("CPACMS.lawyer_court_mapping_creation", $params);
            }
        }
    }

    function getDistrict(Request $request)
    {
        $division = $request->input('division');
        $districtList = LGeoDistrict::where('geo_division_id', '=', $division)->get();

        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($districtList as $data){
            $msg .= '<option value="'.$data->geo_district_id.'">'.$data->geo_district_name.'</option>';
        }
        return $msg;
    }

    function getThana(Request $request)
    {
        $district = $request->input('district');
        $thanaList = LGeoThana::where('geo_district_id', '=', $district)->get();

        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($thanaList as $data){
            $msg .= '<option value="'.$data->geo_thana_id.'">'.$data->geo_thana_name.'</option>';
        }
        return $msg;
    }

    function getBranch(Request $request)
    {
        $bank = $request->input('bank');
        //$branchList = Branch::where('bank_id', '=', $bank)->get();
        $branchList = DB::table('L_BRANCH')->where('bank_id', '=', $bank)->get();

        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($branchList as $data){
            $msg .= '<option value="'.$data->branch_id.'">'.$data->branch_name.'</option>';
        }
        return $msg;
    }

    function getIndividualLawyerCaseData(Request $request)
    {
        $lawyer_id = $request->get('lawyer_id');
        $query = "SELECT la.case_id, cm.case_no
  FROM lawyer_assignment la, case_master_info cm
 WHERE la.case_id = cm.case_id AND la.lawyer_id = $lawyer_id";
        $caseData = DB::select(DB::raw($query));

        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($caseData as $data){
            $msg .= '<option value="'.$data->case_id.'">'.$data->case_no.'</option>';
        }
        return $msg;
    }
}
