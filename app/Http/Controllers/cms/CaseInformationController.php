<?php

namespace App\Http\Controllers\cms;

use App\Entities\Cms\CaseDocs;
use App\Entities\Cms\OrganizationParty;
use App\Entities\Cms\OrgPartyEmployee;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Cms\CaseCategory;
use App\Entities\Cms\CaseMasterInfo;
use App\Entities\Cms\CasePartyDtl;
use App\Enums\Pmis\Employee\Statuses;
use App\Enums\YesNoFlag;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDO;

class CaseInformationController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function index(Request $request)
    {
        $editData = '';
        $complainantData = '';
        $defendentData = '';
        $caseDocData = '';

        $querys = "select * from CASE_CATEGORY where ACTIVE_YN = 'Y'";
        $caseCategoryList = DB::select($querys);

        $querys = "SELECT CPACMS.court_list ('') FROM DUAL";
        $courtList = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL";
        $caseStatusList = DB::select($querys);

        $querys = "SELECT CPACMS.authorise_officer_emp FROM DUAL";
        $authOfficerList = DB::select($querys);

        $querys = "SELECT CPACMS.case_information_search_list ('') FROM DUAL" ;
        $caseData = DB::select($querys);

        $querys = "SELECT CPACMS.department_list FROM DUAL" ;
        $authOfficerDeptList = DB::select($querys);

        $orgList = OrganizationParty::orderBy('organization_name', 'asc')->get();

        $querys = "SELECT organization_type_id, organization_type_name FROM l_organization_type" ;
        $org_types = DB::select($querys);

        return view('cms.caseinformation.caseInformation', [
            'editData' => $editData,
            'caseCategoryList' => $caseCategoryList,
            'courtList' => $courtList,
            'caseStatusList' => $caseStatusList,
            'complainantData' => $complainantData,
            'authOfficerList' => $authOfficerList,
            'defendentData' => $defendentData,
            'caseData' => $caseData,
            'orgList' => $orgList,
            'authOfficerDeptList' => $authOfficerDeptList,
            'caseDocData' => $caseDocData,
            'org_types' => $org_types]);
    }

    public function edit(Request $request, $id)
    {
        $editData = CaseMasterInfo::find($id)->toArray();

        $orgList = OrganizationParty::orderBy('organization_name', 'asc')->get();
        $caseCategoryList = CaseCategory::orderBy('category_id', 'asc')->get();
        $querys = "SELECT CPACMS.court_list ('') FROM DUAL";
        $courtList = DB::select($querys);
        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL";
        $caseStatusList = DB::select($querys);

        $querys = "SELECT CPACMS.authorise_officer_emp FROM DUAL";
        $authOfficerList = DB::select($querys);

        $complainantData = CasePartyDtl::where('case_id', '=', $id)->where('party_type_id', '=', '1')->get();
        $defendentData = CasePartyDtl::where('case_id', '=', $id)->where('party_type_id', '=', '2')->get();

        $querys = "SELECT CPACMS.case_document_edit ('" . $id . "', '') FROM DUAL";
        $caseDocData = DB::select($querys);

        $querys = "SELECT CPACMS.case_information_search_list ('') FROM DUAL" ;
        $caseData = DB::select($querys);

        $querys = "SELECT CPACMS.department_list FROM DUAL" ;
        $authOfficerDeptList = DB::select($querys);

        $querys = "SELECT organization_type_id, organization_type_name FROM l_organization_type" ;
        $org_types = DB::select($querys);

        return view('cms.caseinformation.caseInformation', [
            'editData' => $editData,
            'caseCategoryList' => $caseCategoryList,
            'courtList' => $courtList,
            'caseStatusList' => $caseStatusList,
            'complainantData' => $complainantData,
            'authOfficerList' => $authOfficerList,
            'defendentData' => $defendentData,
            'orgList' => $orgList,
            'caseData' => $caseData,
            'authOfficerDeptList' => $authOfficerDeptList,
            'caseDocData' => $caseDocData,
            'org_types' => $org_types,
            'caseEdit' => 'Y']);
    }

    public function caseInformationDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.case_info_grid_data FROM DUAL";
        $caseInfo = DB::select($querys);

        return datatables()->of($caseInfo)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.case-info.case-info-edit', $query->case_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeCaseInformation(Request $request)
    {
//        dd($request->all());
        $response = $this->storeCaseInfo($request);
        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            session()->flash('message', $message);

            unset($request['converted_file']);
            unset($request['case_doc']);

            return redirect()->route('cms.case-info.case-info-index')->withInput();
        }

        if ($response['o_case_id']) {
            $comp_name = $request->get("comp_name");
            $def_name = $request->get("def_name");
            if (isset($def_name) || isset($comp_name)) {
                $getReturnDtl = $this->storeCaseInfoCompDef($request, $response['o_case_id']);
                $fileUploadUpdt = $this->storeCaseFile($request, $response['o_case_id']);
            }
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/case-info');
    }

    public function updateCaseInformation(Request $request)
    {
        //dd($request->all());
        $getReturn = $this->storeCaseInfo($request);
        if ($request->get("case_id")) {
            if ($request->get("comp_name") || $request->get("def_name")) {
                $getReturnDtl = $this->updateCaseInfoCompDef($request, $request->get("case_id"));
                $fileUploadUpdt = $this->updateCaseFile($request, $request->get("case_id"));
            }
        }
        $flashMessageContent = $this->flashMessageManager->getMessage($getReturn);
        return redirect('/case-info')->with($flashMessageContent['class'], $flashMessageContent['message']);
    }

    public function storeCaseInfo($request = [])
    {
//        dd($request);
        $case_id = '';
        if ($request->get("case_id")) {
            $case_id = $request->get("case_id");
        }

        if($request->get("pro_former_defender"))
        {
            $pro_former_defender = 'Y';
        }
        else{
            $pro_former_defender = 'N';
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $case_id_get = sprintf("%4000s", "");
            $params1 = [
                "p_case_id" => $case_id,
                "p_case_no" => $request->get("case_no"),
                "p_previous_case_id" => $request->get("prev_case_no"),
                "p_case_date" => $request->get("case_date"),
                "p_case_description" => $request->get("description"),
                "p_category_id" => $request->get("case_category"),
                "p_court_id" => $request->get("court_id"),
                "p_case_status_id" => $request->get("case_status"),
                "p_authorise_officer" => $request->get("authorise_officer_id"),
                "p_auth_dept_id" => $request->get("authorise_officer_dept"),
                "p_auth_dept_name" => '',
                "p_pro_former_yn" => $pro_former_defender,
                "p_insert_by" => auth()->id(),
                "o_case_id" => &$case_id_get,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_info_creation", $params1);
        } catch (\Exception $e) {
            $params1 = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
//dd($params1);
        return $params1;

    }

    public function storeCaseInfoCompDef($request = [], $case_id)
    {//dd($request->all());
        $postData = $request->post();
        $party_details_id = '';
        $params = '';
        try {
            if (isset($postData['comp_name'])) {
                foreach ($postData['comp_name'] as $indx => $value) {//dd($comp_name);
                    try {
                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        if($postData['comp_org_id'][$indx]!=null){
                            $comp_org_id = $postData['comp_org_id'][$indx];
                        }else{
                            $comp_org_id = '';
                        }
                        if($postData['comp_desig_id'][$indx]!=null){
                            $comp_desig_id = $postData['comp_desig_id'][$indx];
                        }else{
                            $comp_desig_id = '';
                        }
                        /*if($postData['comp_emp_code'][$indx]!=null){
                            $comp_emp_code = $postData['comp_emp_code'][$indx];
                        }else{
                            $comp_emp_code = '';
                        }*/
                        $params1 = [
                            "p_party_details_id" => $party_details_id,
                            "p_party_details_name" => $postData['comp_name'][$indx],
                            "p_party_details_name_bng" => '',
                            "p_party_details_address" => $postData['comp_address'][$indx],
                            "p_party_details_address_bng" => '',
                            "p_party_contact_no" => $postData['comp_contact_no'][$indx],
                            "p_organisation_yn" => ($postData['comp_org_name'] != null) ? YesNoFlag::YES : YesNoFlag::NO,
                            "p_organization_id" => $comp_org_id,
                            "p_organization_name" => $postData['comp_org_name'][$indx],
                            "p_organization_name_bng" => '',
                            "p_party_type_id" => '1',
                            "p_case_id" => $case_id,
                            "p_department_id" => '',
                            "p_department_name" => '',
                            "p_designation_id" => $comp_desig_id,
                            "p_designation" => $postData['comp_desig'][$indx],
                            "p_party_emp_id" => '',
                            "p_org_from" => $postData['comp_chk_complain_from'][$indx],
                            "p_insert_by" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];
                        DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                    }catch (\Exception $e) {
                        dd($e);
                    }
                }
            }

            if ($request->get("def_name")!=null) {
                foreach ($request->get('def_name') as $indx => $value) {
                    try {
                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    if($request->get("def_org_id")[$indx]!=null){
                        $def_org_id = $request->get("def_org_id")[$indx];
                    }else{
                        $def_org_id = '';
                    }
                    if($request->get("def_desig_id")[$indx]!=null){
                        $def_desig_id = $request->get("def_desig_id")[$indx];
                    }else{
                        $def_desig_id = '';
                    }
                    $params = [
                        "p_party_details_id" => $party_details_id,
                        "p_party_details_name" => $request->get('def_name')[$indx],
                        "p_party_details_name_bng" => '',
                        "p_party_details_address" => $request->get('def_address')[$indx],
                        "p_party_details_address_bng" => '',
                        "p_party_contact_no" => $request->get('def_contact_no')[$indx],
                        "p_organisation_yn" => ($request->get('def_org_name')[$indx] != null) ? YesNoFlag::YES : YesNoFlag::NO,
                        "p_organization_id" => $def_org_id,
                        "p_organization_name" => $request->get("def_org_name")[$indx],
                        "p_organization_name_bng" => '',
                        "p_party_id" => '2',
                        "p_case_id" => $case_id,
                        "p_department_id" => '',
                        "p_department_name" => '',
                        "p_designation_id" => $def_desig_id,
                        "p_designation" => $request->get('def_desig')[$indx],
                        "p_party_emp_id" => '',//$request->get('def_emp_code')[$indx],
                        "p_org_from" => $request->get('def_chk_complain_from')[$indx],
                        "p_insert_by" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    DB::executeProcedure("CPACMS.case_party_details_creation", $params);
                    }catch (\Exception $e) {
                        dd($e);
                    }
                }
            }

        } catch (\Exception $e) {

        }
        return $params;
    }

    public function updateCaseInfoCompDef($request = [], $case_id)
    {
        $party_details_id = '';
        $params2 = '';
        try {
            if ($request->get("comp_name")!=null) {
                if($request->get("comp_party_details_id")){
                    foreach ($request->get("comp_party_details_id") as $indx => $value) {
                        CasePartyDtl::where('party_details_id', $request->get("comp_party_details_id")[$indx])->delete();
                    }
                }
                foreach ($request->get('comp_name') as $indx => $value) {

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    if($request->get("comp_org_id")[$indx]!=null){
                        $comp_org_id = $request->get("comp_org_id")[$indx];
                    }else{
                        $comp_org_id = '';
                    }
                    if($request->get("comp_desig_id")[$indx]!=null){
                        $comp_desig_id = $request->get("comp_desig_id")[$indx];
                    }else{
                        $comp_desig_id = '';
                    }
                    /*if($postData['comp_emp_code'][$indx]!=null){
                        $comp_emp_code = $postData['comp_emp_code'][$indx];
                    }else{
                        $comp_emp_code = '';
                    }*/
                    $params1 = [
                        "p_party_details_id" => $party_details_id,
                        "p_party_details_name" => $request->get('comp_name')[$indx],
                        "p_party_details_name_bng" => '',
                        "p_party_details_address" => $request->get('comp_address')[$indx],
                        "p_party_details_address_bng" => '',
                        "p_party_contact_no" => $request->get('comp_contact_no')[$indx],
                        "p_organisation_yn" => ($request->get('comp_org_name')[$indx] != null) ? YesNoFlag::YES : YesNoFlag::NO,
                        "p_organization_id" => $comp_org_id,//$request->get("comp_org_id")[$indx],
                        "p_organization_name" => $request->get("comp_org_name")[$indx],
                        "p_organization_name_bng" => '',
                        "p_party_id" => '1',
                        "p_case_id" => $case_id,
                        "p_department_id" => '',
                        "p_department_name" => '',
                        "p_designation_id" => $comp_desig_id,//$request->get('comp_desig_id')[$indx],
                        "p_designation" => $request->get('comp_desig')[$indx],
                        "p_party_emp_id" => '',//$request->get('comp_emp_code')[$indx],
                        "p_org_from" => $request->get('comp_chk_complain_from')[$indx],
                        "p_insert_by" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                }
            }

            if ($request->get("def_name")!=null) {
                if($request->get("def_party_details_id")){
                    foreach ($request->get("def_party_details_id") as $indx => $value) {
                        CasePartyDtl::where('party_details_id', $request->get("def_party_details_id")[$indx])->delete();
                    }
                }
                foreach ($request->get('def_name') as $indx => $value) {

                    $status_code = sprintf("%4000s", "");
                    $status_message = sprintf("%4000s", "");
                    if($request->get("def_org_id")[$indx]!=null){
                        $def_org_id = $request->get("def_org_id")[$indx];
                    }else{
                        $def_org_id = '';
                    }
                    if($request->get("def_desig_id")[$indx]!=null){
                        $def_desig_id = $request->get("def_desig_id")[$indx];
                    }else{
                        $def_desig_id = '';
                    }
                    $params2 = [
                        "p_party_details_id" => $party_details_id,
                        "p_party_details_name" => $request->get('def_name')[$indx],
                        "p_party_details_name_bng" => '',
                        "p_party_details_address" => $request->get('def_address')[$indx],
                        "p_party_details_address_bng" => '',
                        "p_party_contact_no" => $request->get('def_contact_no')[$indx],
                        "p_organisation_yn" => ($request->get('def_org_name')[$indx] != null) ? YesNoFlag::YES : YesNoFlag::NO,
                        "p_organization_id" => $def_org_id,
                        "p_organization_name" => $request->get("def_org_name")[$indx],
                        "p_organization_name_bng" => '',
                        "p_party_id" => '2',
                        "p_case_id" => $case_id,
                        "p_department_id" => '',
                        "p_department_name" => '',
                        "p_designation_id" => $def_desig_id,
                        "p_designation" => $request->get('def_desig')[$indx],
                        "p_party_emp_id" => '',//$request->get('def_emp_code')[$indx],
                        "p_org_from" => $request->get('def_chk_complain_from')[$indx],
                        "p_insert_by" => auth()->id(),
                        "o_status_code" => &$status_code,
                        "o_status_message" => &$status_message
                    ];
                    DB::executeProcedure("CPACMS.case_party_details_creation", $params2);
                }
            }

        } catch (\Exception $e) {

        }
        return $params2;
    }

    public function storeCaseFile($request = [], $case_id)
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
                "p_case_id" => $case_id,
                "p_initial_yn" => 'Y',
                "p_case_update_id" => '',
                "p_doc_description" => $request->get("doc_description")[$indx],
                "p_decree_yn" => '',
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_documents_store", $params1);
        }
    }

    public function updateCaseFile($request = [], $case_id)
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
                "p_case_id" => $case_id,
                "p_initial_yn" => 'Y',
                "p_case_update_id" => '',
                "p_doc_description" => $request->get("doc_description")[$indx],
                "p_decree_yn" => '',
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_documents_store", $params1);
        }
    }

    public function caseInformationDownload(Request $request, $id)
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

    public function removePartyData(Request $request)
    {
        $getReturn = $this->removePartyDataInfo($request);
        if ($getReturn['o_status_code'] == '1') {
            $result = 'success';
        } else {
            $result = 'failure';
        }
        return $result;
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

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->findEmployeeCodesBy($searchTerm);
        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        return $this->findEmployeeInformation($empId);
    }

    public function findEmployeeInformation($employeeId)
    {
        $querys = "SELECT CPACMS.employee_data_list('" . $employeeId . "') from dual";
        $employee = DB::selectOne(DB::raw($querys));

        if ($employee) {
            $jsonEncodedEmployee = json_encode($employee);
            $employeeArray = json_decode($jsonEncodedEmployee, true);
            return $employeeArray;
        }

        return [];
    }

    public function findEmployeeCodesBy($searchTerm)
    {
        return Employee::where(function ($query) use ($searchTerm) {
                $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                    ->orWhere('emp_code', 'like', '%' . trim($searchTerm) . "%");
            })
            ->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);



        /*return Employee::where(
            [
                ['emp_code', 'like', '' . $searchTerm . '%'],
//                ['emp_status_id', '=', Statuses::ON_ROLE],
            ]
        )
//            ->whereIn('emp_status_id', [Statuses::DEPUTATION, Statuses::ON_ROLE])
            ->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);*/

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

    public function removePartyDataInfo($request = [])
    {
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_party_details_id" => $request->get("party_details_id"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_party_details_delete", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
    }

    public function getOrganization(Request $request)
    {
        $searchTerm = $request->get('term');
        return OrganizationParty::where(
            [
                ['organization_name', 'like', '' . $searchTerm . '%']
            ]
        )->orderBy('organization_name', 'ASC')->limit(10)->get(['organization_id', 'organization_name']);
    }

    public function getOrganizationData(Request $request, $empId)
    {
        $employee = OrgPartyEmployee::where(
            [
                ['organization_id', 'like', '' . $empId . '%']
            ]
        )->get(['org_party_emp_id', 'employee_name']);

        return $employee;

//        if ($employee) {
//            $jsonEncodedEmployee = json_encode($employee);
//            $employeeArray = json_decode($jsonEncodedEmployee, true);
//            return $employeeArray;
//        }
//        $employee = OrganizationParty::where(
//            [
//                ['organization_id', 'like', '' . $empId . '%']
//            ]
//        )->get(['authorize_emp_name', 'address', 'contact_no', 'authorize_emp_designation']);
//
//        if ($employee) {
//            $jsonEncodedEmployee = json_encode($employee);
//            $employeeArray = json_decode($jsonEncodedEmployee, true);
//            return $employeeArray;
//        }
//
//        return [];
    }

    public function getEmployeeData(Request $request)
    {
        $empName = $request->get('emp_name');

        $employee = OrgPartyEmployee::where(
            [
                ['employee_name', 'like', '' . $empName . '%']
            ]
        )->get(['employee_designation', 'address', 'contact_no']);

        return $employee;
    }

    public function storeOrganization(Request $request)
    {
        $getReturn = $this->storeOrganizationData($request);
        $result = ['code' => $getReturn['o_status_code'], 'msg' => $getReturn['o_status_message']];
        return $result;
    }

    public function storeOrganizationData($request = [])
    {
        $organization_id = '';
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_organization_id" => $organization_id,
                "p_organization_name" => $request->get("modal_org_name"),
                "p_organization_name_bng" => $request->get("modal_org_name_ban"),
                "p_authorize_emp_name" => $request->get("modal_emp_name"),
                "p_authorize_emp_name_bng" => $request->get("modal_emp_name_ban"),
                "p_authorize_emp_designation" => $request->get("modal_desig"),
                "p_active_yn" => ($request->get('active_status') != null) ? YesNoFlag::YES : YesNoFlag::NO,
                "p_outsider_yn" => ($request->get('outsider_status') != null) ? YesNoFlag::YES : YesNoFlag::NO,
                "p_address" => $request->get("modal_adress"),
                "p_contact_no" => $request->get("modal_contact"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];//dd($params);
            DB::executeProcedure("CPACMS.organization_party_creation", $params);

        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

}
