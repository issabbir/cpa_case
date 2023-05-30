<?php

namespace App\Http\Controllers\cms;

use App\Entities\Cms\CaseDocs;
use App\Entities\Cms\CaseMasterInfo;
use App\Entities\Cms\CasePartyDtl;
use App\Entities\Cms\CasePartyTypes;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;
use Yajra\DataTables\DataTables;

class CaseInformationUpdateController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $editData   = '';
        $caseDocData = '';
        $lawyerList = '';

        $querys = "SELECT CPACMS.case_information_search_list ('') FROM DUAL" ;
        $caseData = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL" ;
        $caseStatusList = DB::select($querys);

        $querys = "SELECT CPACMS.bill_info_service_name_list FROM DUAL" ;
        $serviceList = DB::select($querys);

        $casePartyTypes = CasePartyTypes::all();

        return view('cms.caseinfoupdate.caseInfoUpdate', [
            'editData' => $editData,
            'caseStatusList' => $caseStatusList,
            'lawyerList' => $lawyerList,
            'caseDocData' => $caseDocData,
            'serviceList' => $serviceList,
            'casePartyTypes' => $casePartyTypes,
            'caseData' => $caseData]);
    }

    public function edit(Request $request, $id)
    {
        $querys = "SELECT CPACMS.case_information_search_list ('') FROM DUAL" ;
        $caseData = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL" ;
        $caseStatusList = DB::select($querys);

        $querys = "SELECT CPACMS.bill_info_service_name_list FROM DUAL" ;
        $serviceList = DB::select($querys);

        $querys = "SELECT CPACMS.case_update_info_grid_edit ('".$id."') FROM DUAL" ;
        $editData = DB::select($querys);

        $querys = "SELECT CPACMS.case_lawyer_info_list ('".$editData[0]->case_id."') FROM DUAL" ;
        $lawyerList = DB::select($querys);

        $querys = "SELECT CPACMS.case_document_edit ('".$editData[0]->case_id."', '".$id."') FROM DUAL";
        $caseDocData = DB::select($querys);

        $casePartyTypes = CasePartyTypes::all();

        return view('cms.caseinfoupdate.caseInfoUpdate', [
            'editData' => $editData,
            'caseStatusList' => $caseStatusList,
            'lawyerList' => $lawyerList,
            'caseDocData' => $caseDocData,
            'serviceList' => $serviceList,
            'casePartyTypes' => $casePartyTypes,
            'caseData' => $caseData]);
    }

    public function storeCaseInfoUpdate(Request $request)
    {
        $getReturn = $this->storeCaseUpdateInfo($request);

        if($getReturn['o_case_update_id'])
        {
            $fileUploadUpdt = $this->storeCaseFile($request, $getReturn['o_case_update_id']);
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($getReturn);
        return redirect('/case-info-update')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function updateCaseInfoUpdate(Request $request)
    {
        $getReturn = $this->storeCaseUpdateInfo($request);
        if($getReturn['o_case_update_id'])
        {
            $fileUploadUpdt = $this->updateCaseFile($request, $getReturn['o_case_update_id']);
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($getReturn);
        return redirect('/case-info-update')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function getCaseMasterData(Request $request)
    {
        $case_no = $request->get("case_no");
        $querys = "SELECT CPACMS.case_information_search_select ('".$case_no."') FROM DUAL" ;
        $caseMasterData = DB::select($querys);
        return $caseMasterData;
    }

    public function getComplainantData(Request $request)
    {
        $case_no = $request->get("case_no");
        $complainantData = CasePartyDtl::where('case_id', '=', $case_no)->where('party_type_id', '=', '1')->get();
        return (DataTables::of($complainantData)->addIndexColumn()->make(true));
    }

    public function getDefendentData(Request $request)
    {
        $case_no = $request->get("case_no");
        $defendentData = CasePartyDtl::where('case_id', '=', $case_no)->where('party_type_id', '=', '2')->get();
        return (DataTables::of($defendentData)->addIndexColumn()->make(true));
    }

    function getLawyer(Request $request)
    {
        $case_no = $request->get("case_no");

        $querys = "SELECT CPACMS.case_lawyer_info_list ('".$case_no."') FROM DUAL" ;
        $caseMasterData = DB::select($querys);

        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($caseMasterData as $data){
            $msg .= '<option value="'.$data->pass_value.'">'.$data->show_value.'</option>';
        }
        return $msg;
    }

    public function caseInfoUpdateDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.case_update_info_grid_list ('') FROM DUAL" ;
        $caseInfoUpdateGridList = DB::select($querys);

        return datatables()->of($caseInfoUpdateGridList)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.case-info-update.case-info-update-edit', $query->case_update_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function getAllCase(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = CaseMasterInfo::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('upper(case_no)'), 'like', strtoupper('%' . trim($searchTerm) . '%'));
        })->orderBy('case_id', 'ASC')->limit(10)->get(['case_id', 'case_no']);

        return $empId;
    }

    public function storeCaseUpdateInfo($request = [])
    {
        $case_update_id = '';
        if($request->get("case_update_id"))
        {
            $case_update_id = $request->get("case_update_id");
        }
        if($request->get("win_party")){
            $win_party = $request->get("win_party");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $o_case_update_id = sprintf("%4000s", "");
            $params = [
                "p_case_update_id" => $case_update_id,
                "p_case_update_date" => $request->get("last_update_date"),
                "p_comments" => $request->get("comments"),
                "p_next_date" => $request->get("next_date"),
                "p_reason" => $request->get("reason"),
                "p_description" => $request->get("description"),
                "p_case_id" => $request->get("case_no"),
                "p_case_status_id" => $request->get("case_status"),
                "p_lawyer_id" => $request->get("lawyer"),
                "p_assignment_type_id" => $request->get("assignment_type_id"),
                "p_judges_name" => $request->get("judges_name"),
                "p_winner_party_id" => isset($win_party) ? ($win_party) : '',
                "p_insert_by" => auth()->id(),
                "o_case_update_id" => &$o_case_update_id,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_info_update_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function storeCaseFile($request = [], $case_update_id)
    {
        foreach ($request->get("case_doc") as $indx => $value) {
            $data = $request->get("case_doc")[$indx];
            $case_doc = substr($data, strpos($data, ",") + 1);
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params1 = [
                "p_case_doc_id" => '',
                "p_case_doc_name" => $request->get("case_doc_name")[$indx],
                "p_case_doc_name_bng" => '',
                "p_case_doc" => ['value' => $case_doc, 'type' => PDO::PARAM_LOB],
                "p_case_doc_type" => $request->get("case_doc_type")[$indx],
                "p_case_id" => $request->get('case_no'),
                "p_initial_yn" => 'N',
                "p_case_update_id" => $case_update_id,
                "p_doc_description" => $request->get("doc_description")[$indx],
                "p_decree_yn" => $request->get("final_doc")[$indx],
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_documents_store", $params1);
        }
    }

    public function updateCaseFile($request = [], $case_update_id)
    {
        $case_doc_id = '';
        if ($request->get("case_doc_id")) {
            $case_doc_id = $request->get("case_doc_id");
        }
        if($case_doc_id){
            foreach ($case_doc_id as $indx => $value) {
                CaseDocs::where('case_doc_id', $case_doc_id[$indx])->delete();
            }
        }

        foreach ($request->get("case_doc") as $indx => $value) {
            $data = $request->get("case_doc")[$indx];
            $case_doc = substr($data, strpos($data, ",") + 1);
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params1 = [
                "p_case_doc_id" => '',
                "p_case_doc_name" => $request->get("case_doc_name")[$indx],
                "p_case_doc_name_bng" => '',
                "p_case_doc" => ['value' => $case_doc, 'type' => PDO::PARAM_LOB],
                "p_case_doc_type" => $request->get("case_doc_type")[$indx],
                "p_case_id" => $request->get('case_no'),
                "p_initial_yn" => 'N',
                "p_case_update_id" => $case_update_id,
                "p_doc_description" => $request->get("doc_description")[$indx],
                "p_decree_yn" => $request->get("final_doc")[$indx],
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_documents_store", $params1);
        }
    }

    public function caseInformationUpdateDownload($id)
    {
        $caseDocData = CaseDocs::find($id);

        if ($caseDocData) {
            if ($caseDocData->case_doc && $caseDocData->case_doc_name && $caseDocData->case_doc_type) {
                $content = base64_decode($caseDocData->case_doc);

                return response()->make($content, 200, [
                    'Content-Type' => $caseDocData->case_doc_type,
                    'Content-Disposition' => 'attachment; filename="' . $caseDocData->case_doc_name . '"'
                ]);
            }
        }
    }

    public function removeCaseDoc($request = [])
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_case_doc_id" => $request->get("case_doc_id"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_document_delete", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
    }

    public function caseDocRemove(Request $request)
    {
        $getReturn = $this->removeCaseDoc($request);
        if ($getReturn['o_status_code'] == '1') {
            $result = 'success';
        } else {
            $result = 'failure';
        }
        return $result;
    }
}
