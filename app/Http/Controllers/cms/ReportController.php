<?php

namespace App\Http\Controllers\cms;

use App\Entities\Admin\LGeoDivision;
use App\Entities\Cms\CaseCategory;
use App\Entities\Cms\CasePartyTypes;
use App\Entities\Cms\CourtCategory;
use App\Entities\Cms\L_Department;
use App\Entities\Cms\LMonth;
use App\Entities\Cms\OrganizationParty;
use App\Enums\ModuleInfo;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Entities\Security\Report;

class ReportController extends Controller
{
    private $flashMessageManager;

    public function __construct(FlashMessageManager $flashMessageManager)
    {
        $this->flashMessageManager = $flashMessageManager;
    }

    public function reportGenerator(Request $request)
    {
        $module = ModuleInfo::WMS_CASE_ID;

        $reportObject = new Report();

        if (auth()->user()->hasGrantAll()) {
            $reports = $reportObject->where('module_id', $module)->orderBy('report_name', 'ASC')->get();
        } else {
            $roles = auth()->user()->getRoles();
            $reports = array();
            foreach ($roles as $role) {
                if (count($role->reports)) {
                    $rpts = $role->reports->where('module_id', $module);
                    foreach ($rpts as $report) {
                        $reports[$report->report_id] = $report;
                    }
                }
            }
        }

        $reportList = '';

        //return view('cms.reportgenerator.index', ['reportList' => $reportList]);
        return view('cms.reportgenerator.index', compact('reports'));
    }

    function getOrg(Request $request)
    {
        $organizationPartyList = OrganizationParty::orderBy('organization_name', 'asc')->get();
        $msg = '<option value="">-- Please select an option --</option>';
        foreach ($organizationPartyList as $data){
            $msg .= '<option value="'.$data->organization_id.'">'.$data->organization_name.'</option>';
        }
        /*view('cms.reportgenerator.report-params', compact('report'), [
            'msg'=> $msg
        ])->render();*/
        return $msg;
    }

    public function reportParams(Request $request, $id)
    {
        $report = Report::find($id);
        $querys = "SELECT CPACMS.court_list ('') FROM DUAL";
        $courtList = DB::select($querys);

        $querys = "SELECT CPACMS.lawyer_master_info_list_bill FROM DUAL";
        $lawyerList = DB::select($querys);

        $querys = "SELECT CPACMS.case_status_list ('') FROM DUAL";
        $caseStatusList = DB::select($querys);

        $divisions = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        $departments = L_Department::orderBy('department_name', 'asc')->get();
        $caseCategoryList = CaseCategory::orderBy('category_id', 'asc')->get();
        $courtCategoryList = CourtCategory::orderBy('court_category_id', 'asc')->get();

        $casePartyTypes = CasePartyTypes::all();

        $querys = "select to_char (to_date (rownum, 'MM'), 'fmMonth') as month from dual connect by level <= 12";
        $monthList = DB::select($querys);

        $querys = "SELECT TO_CHAR (SYSDATE, 'RRRR') - 30 + ROWNUM AS year FROM DUAL CONNECT BY LEVEL <= 60";
        $yearList = DB::select($querys);

        $querys = "select 'YES' as status, 'Y' as status_id from dual union all select 'NO' as status, 'N' as status_id from dual";
        $orgStatusList = DB::select($querys);

        $months = LMonth::orderBy('month_id', 'asc')->get();

        $reportForm = view('cms.reportgenerator.report-params', compact('report'), [
            'months' => $months,
            'courtList' => $courtList,
            'departmentList' => $departments,
            'caseCategoryList' => $caseCategoryList,
            'divisions' => $divisions,
            'lawyerList' => $lawyerList,
            'casePartyTypes' => $casePartyTypes,
            'monthList' => $monthList,
            'yearList' => $yearList,
            'orgStatusList' => $orgStatusList,
            'caseStatusList' => $caseStatusList,
            'courtCategoryList' => $courtCategoryList
        ])->render();
        return $reportForm;
    }
}
