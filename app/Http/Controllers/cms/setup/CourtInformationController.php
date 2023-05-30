<?php

namespace App\Http\Controllers\cms\setup;

use App\Entities\Cms\OrganizationParty;
use App\Entities\Admin\LGeoDistrict;
use App\Entities\Admin\LGeoDivision;
use App\Entities\Admin\LGeoThana;
use App\Entities\Cms\CourtCategory;
use App\Entities\Cms\CourtMasterInfo;
use App\Entities\Cms\OrgPartyEmployee;
use App\Http\Controllers\Controller;
use App\Managers\FlashMessageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourtInformationController extends Controller
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
        $thanaList = '';

        $querys = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys);

        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        $courtList = CourtCategory::orderBy('court_category_id', 'asc')->get();

        return view('cms.setup.courtinformation.courtInformation', [
            'editData' => $editData,
            'active_yn' => $active_yn,
            'divisionList' => $divisionList,
            'districtList' => $districtList,
            'thanaList' => $thanaList,
            'courtList' => $courtList]);
    }

    public function edit(Request $request, $id)
    {
        $querys = "SELECT CPACMS.act_inc_list FROM DUAL" ;
        $active_yn = DB::select($querys);

        $divisionList = LGeoDivision::orderBy('geo_division_id', 'asc')->get();
        $courtList = CourtCategory::orderBy('court_category_id', 'asc')->get();

        $editData = CourtMasterInfo::find($id)->toArray();

        $division_id =  $editData['division_id'];
        $district_id =  $editData['district_id'];

        $districtList = LGeoDistrict::where('geo_division_id', '=', $division_id)->get();
        $thanaList = LGeoThana::where('geo_district_id', '=', $district_id)->get();

        return view('cms.setup.courtinformation.courtInformation', [
            'editData' => $editData,
            'active_yn' => $active_yn,
            'divisionList' => $divisionList,
            'districtList' => $districtList,
            'thanaList' => $thanaList,
            'courtList' => $courtList]);
    }

    public function storeCourtInformation(Request $request)
    {
        $response = $this->storeCourtInfo($request);
        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/court-info');
    }

    public function updateCourtInformation(Request $request)
    {
        $response = $this->storeCourtInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/court-info');
    }

    public function courtInformationDatatable(Request $request)
    {
        $querys = "SELECT CPACMS.COURT_MASTER_INFO_GRID_LIST FROM DUAL" ;
        $courtInformation = DB::select($querys);

        return datatables()->of($courtInformation)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.court-info.court-info-edit', $query->court_id) . '"><i class="bx bx-edit cursor-pointer"></i></a>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function storeCourtInfo($request = [])
    {
        $court_id = '';
        $court_name_bng = '';
        $court_address_bng = '';
        if($request->get("court_id"))
        {
            $court_id = $request->get("court_id");
        }

        if($request->get("court_name_bng"))
        {
            $court_name_bng = $request->get("court_name_bng");
        }

        if($request->get("court_address_bng"))
        {
            $court_address_bng = $request->get("court_address_bng");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_court_id" => $court_id,
                "p_court_name" => $request->get("court_name"),
                "p_court_name_bng" => $court_name_bng,
                "p_court_address" => $request->get("court_address"),
                "p_court_address_bng" => $court_address_bng,
                "p_description" => $request->get("description"),
                "p_active_yn" => $request->get("active_yn"),
                "p_court_category_id" => $request->get("court_category"),
                "p_thana_id" => $request->get("thana"),
                "p_district_id" => $request->get("district"),
                "p_division_id" => $request->get("division"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.COURT_MASTER_INFO_CREATION", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function organizationRegistration()
    {
        $editData   = '';

        $querys = "SELECT organization_type_id, organization_type_name FROM l_organization_type" ;
        $org_types = DB::select($querys);

        return view('cms.setup.organizationregistration.organizationRegistration', compact('editData', 'org_types'));
    }

    public function storeOrganization(Request $request)
    {
        $response = $this->storeOrganizationInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/organization-registration');
    }

    public function ajaxStoreOrganization(Request $request)
    {
        return $this->storeOrganizationInfo($request);
    }

    public function storeOrganizationInfo($request = [])
    {
        $org_id = '';
        if($request->get("org_id"))
        {
            $org_id = $request->get("org_id");
        }
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_organization_id" => $org_id,
                "p_organization_type_id" => $request->get("org_type"),
                "p_organization_name" => $request->get("org_name"),
                "p_organization_name_bng" => $request->get("org_bangla"),
                "p_active_yn" => $request->get("active"),
                "p_outsider_yn" => $request->get("outsider"),
                "p_address" => $request->get("address"),
                "p_email" => $request->get("email"),
                "p_faxno" => $request->get("fax"),
                "p_contact_no" => $request->get("contact_no"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.organization_party_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params;
    }

    public function organizationDatatable(Request $request)
    {
//        $querys = "SELECT organization_id, organization_name, organization_name_bng, address, email, faxno, contact_no,
//(SELECT organization_type_name FROM l_organization_type l WHERE  o.organization_type_id = l.organization_type_id) AS organization_type
//        FROM organization_party o" ;
        $querys = "SELECT organization_id, organization_name, address, email, faxno, contact_no,
(SELECT COUNT(org_party_emp_id) FROM org_party_employee op WHERE o.organization_id = op.organization_id) AS number_of_emp
        FROM organization_party o ORDER BY insert_date desc, update_date desc";
        $orgList = DB::select($querys);

        return datatables()->of($orgList)
            ->addColumn('action', function ($query) {
                return '<a href="' . route('cms.court-info.org-edit', $query->organization_id) . '"><button class="btn btn btn-dark shadow mb-1 btn-secondary" style="margin:5px;">Edit</button></a><button type="button" id="append" class="btn btn btn-dark shadow mb-1 btn-secondary add-org-comp" onclick="modalFunc('.$query->organization_id.')" style="margin:5px;">Add Employee</button>';
            })
//            ->addColumn('other', function ($query) {
//                return '<a href="' . route('cms.court-info.org-edit', $query->organization_id) . '"><button class="btn btn btn-dark shadow mb-1 btn-secondary">Add Employee</button></a>';
//            })
            ->addIndexColumn()
            ->make(true);
    }

    public function org_edit(Request $request, $id)
    {
        $editData = OrganizationParty::find($id)->toArray();

        $querys = "SELECT organization_type_id, organization_type_name FROM l_organization_type" ;
        $org_types = DB::select($querys);

        return view('cms.setup.organizationregistration.organizationRegistration', [
            'editData' => $editData,
            'org_types' => $org_types]);
    }

    public function updateOrganization(Request $request, $id)
    {
//        dd($request);
        $response = $this->storeOrganizationInfo($request);

        $message = $response['o_status_message'];

        if($response['o_status_code'] != 1) {
            session()->flash('m-class', 'alert-danger');
            return redirect()->back()->with('message', $message)->withInput();
        }

        session()->flash('m-class', 'alert-success');
        session()->flash('message', $message);

        return redirect('/organization-registration');
    }

    public function get_org_name(Request $request)
    {
        $id = $request->get('id');
        $querys = "SELECT organization_id, organization_name, address, contact_no from organization_party where organization_id = '$id'" ;
        $org_name = DB::select($querys);

        return $org_name;
    }

    public function storeEmployee(Request $request)
    {
        $getReturn = $this->storeEmployeeData($request);
        $result = ['code' => $getReturn['o_status_code'], 'msg' => $getReturn['o_status_message']];
//        if(!empty($request->get('org_emp_id')))
//        {
//            return redirect('/organization-registration');
//        }
//        else
//        {
            return $result;
//        }
    }

    public function storeEmployeeData($request = [])
    {
        $org_emp_id = '';
        if(!empty($request->get("org_emp_id")))
        {
            $org_emp_id = $request->get("org_emp_id");
        }
        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_org_party_emp_id" => $org_emp_id,
                "p_organization_id" => $request->get('modal_org_id'),
                "p_employee_name" => $request->get("modal_emp_name"),
                "p_employee_name_bng" => $request->get("modal_emp_name_ban"),
                "p_employee_designation" => $request->get("modal_desig"),
                "p_active_yn" => ($request->get('active_status')),
                "p_outsider_yn" => ($request->get('outsider_status')),
                "p_address" => $request->get("modal_address"),
                "p_contact_no" => $request->get("modal_contact"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
//            dd($params);
            DB::executeProcedure("CPACMS.org_party_emp_creation", $params);

        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function employeeDatatable(Request $request)
    {
        $id = $request->get('id');

        $querys = "SELECT org_party_emp_id, employee_name, employee_designation, address, contact_no, (SELECT organization_name FROM organization_party op WHERE  oe.organization_id = op.organization_id) AS organization_name
        FROM org_party_employee oe WHERE organization_id = '$id'" ;
        $empList = DB::select($querys);

        return datatables()->of($empList)
            ->addColumn('action', function ($query) {
                return '<button class="btn btn btn-dark shadow mb-1 btn-secondary" onclick="modalEmp('.$query->org_party_emp_id.')" style="margin:5px;">Edit</button>';
            })
            ->addIndexColumn()
            ->make(true);
    }

    public function emp_edit(Request $request)
    {
        $id = $request->get('id');
        $editData = OrgPartyEmployee::find($id)->toArray();
        return $editData;
    }
}
