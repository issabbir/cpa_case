<?php

namespace App\Http\Controllers\cms;

use App\Entities\Admin\LGeoCountry;
use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoDivision;
use App\Entities\Admin\LGeoThana;
use App\Entities\Cms\CaseConvictedLaw;
use App\Entities\Cms\CourtCategory;
use App\Entities\Cms\MobileCaseInfo;
use App\Entities\Cms\MobileCourtPlace;
use App\Entities\Eqms\BerthOperator;
use App\Entities\Eqms\L_Parts;
use App\Entities\Pmis\Employee\Employee;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PDF;

class MobileCourtController extends Controller
{
    public function index()
    {
        //$districtList = '';
        //$thanaList = '';

        $querys = "select * from CASE_CATEGORY where ACTIVE_YN = 'Y'";
        $caseCategoryList = DB::select($querys);

        $querys = "SELECT CPACMS.court_list ('') FROM DUAL";
        $courtList = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL";
        $caseStatusList = DB::select($querys);

        $courtCategoryList = CourtCategory::orderBy('court_category_id', 'asc')->get();
        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        $convictedLaw = CaseConvictedLaw::orderBy('convicted_id', 'asc')->get();
        $courtPlace = MobileCourtPlace::orderBy('court_place_id', 'asc')->get();
        //$districtList = LGeoDistrict::where('geo_division_id', '=', '2')->get();

        return view('cms.mobilecourtinfo.index', [
            'caseCategoryList' => $caseCategoryList,
            'courtList' => $courtList,
            'courtCategoryList' => $courtCategoryList,
            'divisionList' => $divisionList,
            'districtList'=> $this->commonDropDownLookupsList(array('pmis.l_geo_district','geo_district_id','geo_district_name'),'203'," where geo_division_id = 2"),
            'thanaList'=> $this->commonDropDownLookupsList(array('pmis.l_geo_thana','geo_thana_id','geo_thana_name'),'20319'," where geo_district_id = 203"),
            /*'districtList' => $districtList,*/
            /*'thanaList' => $thanaList,*/
            'convictedLaw' => $convictedLaw,
            'caseStatusList' => $caseStatusList,
            'courtPlace' => $courtPlace,
        ]);
    }

    public function commonDropDownLookupsList($parameterArray = array(),$columnSelected = null,$condition=null,$returnFormat=null){
        //commonDropDownLookupsList(array('v_division','division_id','division_name'),2,'where division_id = 2')
        if(count($parameterArray)>2) {
            $tableName = $parameterArray[0];
            $pass_value = $parameterArray[1];
            $show_value = $parameterArray[2];
        }
        $entityOption = [];
        $query = '';

        if((isset($condition)== true)&& count($parameterArray)>2){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ".$condition." ";
            $querySetFlag = true;
        }else if(count($parameterArray)>2){
            $query = "Select ".$pass_value." as pass_value,".$show_value." as show_value from ".$tableName." ";
            $querySetFlag = true;
        }else{
            $querySetFlag = false;
        }
        $entityOption[] = "<option value=''>Please select an option</option>";
        if($querySetFlag){
            $entityList = DB::select($query);
            foreach ($entityList as $item) {
                $entityOption[] = "<option value='".$item->pass_value."' ".($columnSelected == $item->pass_value ? 'selected ':'').">".$item->show_value."</option>";
            }
        }

        if($returnFormat=='json'){
            return response()->json($entityOption);
        }
        return $entityOption; //default array return format;
    }

    public function getEmp(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = Employee::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(emp_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'))
                ->orWhere('emp_code', 'like', '' . trim($searchTerm) . '%');
        //})->where('emp_status_id','13')->orWhere('emp_status_id','1')->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);
        })->orderBy('emp_id', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name', 'designation_id', 'dpt_department_id']);

        return $empId;
    }

    public function getLaw(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = CaseConvictedLaw::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(convicted_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('convicted_name', 'ASC')->limit(10)->get(['convicted_id', 'convicted_name']);

        return $empId;
    }

    public function getCourtPlace(Request $request)
    {
        $searchTerm = $request->get('term');
        $empId = MobileCourtPlace::where(function ($query) use ($searchTerm) {
            $query->where(DB::raw('LOWER(court_place_name)'), 'like', strtolower('%' . trim($searchTerm) . '%'));
        })->orderBy('court_place_name', 'ASC')->limit(10)->get(['court_place_id', 'court_place_name']);

        return $empId;
    }

    public function dataTableList()
    {
        $queryResult = MobileCaseInfo::orderBy('insert_date','DESC')->get();
        return datatables()->of($queryResult)
            ->addColumn('case_date', function ($query) {
                if ($query->case_date == null) {
                    return '--';
                } else {
                    return Carbon::parse($query->case_date)->format('d-m-Y');
                }
            })
            ->addColumn('fines_amount', function ($query) {
                if ($query->fines_amount == null) {
                    return '--';
                } else {
                    return $query->fines_amount;
                }
            })
            ->addColumn('action', function ($query) {
                //$actionBtn = '<a title="Edit" href="' . route('cms.mobile-court-info.mobile-court-edit', [$query->case_id]) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
                //return $actionBtn;
                return '<a target="_blank" href="'.request()->root().'/report/render?xdo=/~weblogic/CPA_CMS/RPT_CASE_SLIP.xdo&P_CASE_ID='.$query->case_id.'&type=pdf&filename=case_slip" title ="Download" ><i class="bx bx-download cursor-pointer"></i></a>&nbsp;|&nbsp;'.'<a href="' . route('cms.mobile-court-info.mobile-court-edit', [$query->case_id]) . '" title ="Edit" ><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->escapeColumns([])
            ->addIndexColumn()
            ->make(true);
    }

    public function edit(Request $request, $id)
    {
        $data = MobileCaseInfo::select('*')
            ->where('case_id', '=', $id)
            ->first();

        $querys = "select * from CASE_CATEGORY where ACTIVE_YN = 'Y'";
        $caseCategoryList = DB::select($querys);

        $querys = "SELECT CPACMS.court_list ('') FROM DUAL";
        $courtList = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL";
        $caseStatusList = DB::select($querys);

        $courtCategoryList = CourtCategory::orderBy('court_category_id', 'asc')->get();
        $convictedLaw = CaseConvictedLaw::orderBy('convicted_id', 'asc')->get();
        $courtPlace = MobileCourtPlace::orderBy('court_place_id', 'asc')->get();
        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();

        $districtList = LGeoDistrict::where('geo_division_id', '=', '2')->get();

        $thanaList = LGeoThana::where('geo_district_id', '=', $data->district_id)->get();

        return view('cms.mobilecourtinfo.index', [
            'data' => $data,
            'caseCategoryList' => $caseCategoryList,
            'courtList' => $courtList,
            'courtCategoryList' => $courtCategoryList,
            'divisionList' => $divisionList,
            'districtList'=> $this->commonDropDownLookupsList(array('pmis.l_geo_district','geo_district_id','geo_district_name'),$data->district_id," where geo_division_id = 2"),
            'thanaList'=> $this->commonDropDownLookupsList(array('pmis.l_geo_thana','geo_thana_id','geo_thana_name'),$data->thana_id),
            /*'districtList' => $districtList,
            'thanaList' => $thanaList,*/
            'convictedLaw' => $convictedLaw,
            'caseStatusList' => $caseStatusList,
            'courtPlace' => $courtPlace,
        ]);
    }

    public function post(Request $request)
    {
        $response = $this->ins_upd($request);
        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cms.mobile-court-info.mobile-court-index');
    }

    public function update(Request $request, $id)
    {
        $response = $this->ins_upd($request, $id);

        $message = $response['o_status_message'];
        if ($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', 'error|' . $message);
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect()->route('cms.mobile-court-info.mobile-court-index');
    }

    private function ins_upd(Request $request)
    {//dd($request);
        $postData = $request->post();
        if(isset($postData['case_id'])){
            $case_id = $postData['case_id'];
        }else{
            $case_id = '';
        }
        $case_date = $postData['case_date'];
        $case_date = isset($case_date) ? date('Y-m-d', strtotime($case_date)) : '';
        try {
            DB::beginTransaction();
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");

            $params = [
                'p_CASE_ID' => $case_id,
                'p_CASE_NO' => $postData['case_no'],
                'p_CASE_NOTHI_NO' => $postData['case_nothi_no'],
                'p_CASE_DATE' => $case_date,
                'p_CASE_DESCRIPTION' => $postData['case_description'],
                'p_CASE_DESCRIPTION_BNG' =>  $postData['case_description_bng'],
                'p_CATEGORY_ID' => 441,//$postData['category_id'],
                'p_COURT_ID' => '',//$postData['court_id'],
                'p_COURT_CATEGORY_ID' => '',//$postData['court_category_id'],
                'p_THANA_ID' => $postData['thana_id'],
                'p_DISTRICT_ID' => $postData['district_id'],
                'p_DIVISION_ID' => 2,//$postData['division_id'],
                'p_PROSECUTOR_EMP_ID' => $postData['prosecutor_emp_id'],
                'p_GUILTY_PERSON_EMP_YN' => $postData['guilty_person_emp_yn'],
                'p_GUILTY_PERSON_EMP_ID' => isset($postData['guilty_person_emp_id']) ? $postData['guilty_person_emp_id'] : '',
                'p_GUILTY_PERSON_NAME' => isset($postData['guilty_person_name']) ? $postData['guilty_person_name'] : '',
                'p_VICTIM_ADDRESS' => $postData['victim_address'],
                'p_CONVICTED_ID' => $postData['convicted_id'],
                'p_COURT_PLACE_ID' => $postData['court_place_id'],
                'p_EVICTION_YN' => $postData['eviction_yn'],
                'p_FINES_AMOUNT' => $postData['fines_amount'],
                'p_IMPRISONMENT_YEAR' => $postData['imprisonment_year'],
                'p_IMPRISONMENT_MONTH' => $postData['imprisonment_month'],
                'p_IMPRISONMENT_DAY' => $postData['imprisonment_day'],
                'p_UNPAID_IMPRISONMENT_YEAR' => $postData['unpaid_imprisonment_year'],
                'p_UNPAID_IMPRISONMENT_MONTH' => $postData['unpaid_imprisonment_month'],
                'p_UNPAID_IMPRISONMENT_DAY' => $postData['unpaid_imprisonment_day'],
                'p_COMMENTS' => $postData['comments'],
                'p_MAGISTRATE_EMP_ID' => $postData['magistrate_emp_id'],
                'p_CASE_STATUS_ID' => $postData['case_status_id'],
                'P_INSERT_BY' => auth()->id(),
                'o_status_code' => &$status_code,
                'o_status_message' => &$status_message,
            ];
            DB::executeProcedure('CPACMS.MOBILE_CASE_INFO_CREATION', $params);

            if ($params['o_status_code'] != 1) {
                DB::rollBack();
                return $params;
            }
            DB::commit();

        } catch (\Exception $e) {dd($e);
            DB::rollback();
            return ["exception" => true, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function addConvictedLaw(Request $request)
    {
        $convicted_id = '';
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_convicted_id" => $convicted_id,
                "p_convicted_name" => $request->get("convicted_name"),
                "p_convicted_name_bng" => $request->get("convicted_name_bng"),
                "p_active_yn" => 'Y',
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.CASE_CONVICTED_LAW_CREATION", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function addCourtPlace(Request $request)
    {
        $court_place_id = '';
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_court_place_id" => $court_place_id,
                "p_court_place_name" => $request->get("court_place_name"),
                "p_court_place_name_bng" => $request->get("court_place_name_bng"),
                "p_active_yn" => 'Y',
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.MOBILE_COURT_PLACE_CREATION", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }
}
