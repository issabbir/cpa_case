<?php
/**
 * Created by PhpStorm.
 * User: ashraf
 * Date: 2/11/20
 * Time: 11:14 AM
 */

namespace App\Http\Controllers;

use App\Contracts\LookupContract;
use App\Contracts\Pmis\Employee\EmployeeContract;
use App\Contracts\Secdbms\Ims\ImsIncidenceSubTypeContract;
use App\Entities\Secdbms\Ims\ImsIncidence;
use App\Entities\Secdbms\Ims\ImsIncidenceSubtype;
use App\Http\Controllers\Controller;

use App\Managers\Pmis\Employee\EmployeeManager;
use App\Managers\Secdbms\Ims\ImsIncidenceSubTypeManager;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /** @var EmployeeManager */
    private $employeeManager;

    private $lookupManager;

    /** @var ImsIncidenceSubTypeManager */
    private $imsIncidenceSubTypeManager;

    public function __construct(EmployeeContract $employeeManager, LookupContract $lookupManager, ImsIncidenceSubTypeContract $imsIncidenceSubTypeManager)
    {
        $this->employeeManager = $employeeManager;
        $this->lookupManager = $lookupManager;
        $this->imsIncidenceSubTypeManager = $imsIncidenceSubTypeManager;
    }

    public function employees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeeCodesBy($searchTerm);
        return $employees;
    }

    public function employeesWithName(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findEmployeesWithNameBy($searchTerm);

        return $employees;
    }

    public function employeesWithDept(Request $request,$empDept)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDeptWiseEmployeeCodesBy($searchTerm,$empDept);

        return $employees;
    }

    public function employee(Request $request, $empId)
    {
        /** {"emp_id":"1912011700020496","emp_code":"023009","emp_name":"ABDUL KARIM MIAJEE","designation":"LASKAR","designation_id":"157","department":"MARINE","department_id":"1","section":null,"dpt_section_id":null} */
        return $this->employeeManager->findEmployeeInformation($empId);
    }

    public function incidentNumber(Request $request)
    {
        $searchTerm = $request->get('term');
        $imsIncidence = ImsIncidence::select('*')
            ->where(
                [
                    ['incidence_number', 'like', ''.$searchTerm.'%'],
                ]
            )->orderBy('incidence_number', 'ASC')->limit(10)->get();

        return $imsIncidence;
    }

    public function incidenceSubTypes(Request $request)
    {
        $incidentTypeId = $request->get('incidentTypeId');

        if($incidentTypeId) {
            $incidenceSubTypes = $this->imsIncidenceSubTypeManager->findByType($incidentTypeId);
        } else {
            $incidenceSubTypes = [];
        }

        $incidenceSubTypesHtml = view('ajax.incidence-sub-types')->with('incidenceSubTypes', $incidenceSubTypes)->render();

        return [
            'incidenceSubTypesHtml' => $incidenceSubTypesHtml
        ];
    }

    public function incidenceSubTypesName(Request $request, $incidenceTypeId)
    {
        $incidenceSubTypes = ImsIncidenceSubtype::select('*')->where('ims_incidence_subtype.incidence_type_id','=',$incidenceTypeId)->get();

        $incidenceSubTypesHtml = view('ajax.incidence-sub-types')->with('incidenceSubTypes', $incidenceSubTypes)->render();

        return [
            'incidenceSubTypesHtml' => $incidenceSubTypesHtml
        ];
    }


    public function districts(Request $request, $divisionId)
    {
        $districts = [];

        if($divisionId) {
            $districts = $this->lookupManager->findDistrictsByDivision($divisionId);
        }

        $html = view('ajax.districts')->with('districts', $districts)->render();

        return response()->json(array('html'=>$html));
    }

    public function thanas(Request $request, $districtId)
    {
        $thanas = [];

        if($districtId) {
            $thanas = $this->lookupManager->findThanasByDistrict($districtId);
        }

        $html = view('ajax.thanas')->with('thanas', $thanas)->render();

        return response()->json(array('html'=>$html));
    }

    public function opInEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findOpInEmployees($searchTerm);

        return $employees;
    }

    public function areaInsEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findAreaInsEmployees($searchTerm);

        return $employees;
    }

    public function secOffEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findSecOffEmployees($searchTerm);

        return $employees;
    }

    public function deputyDirAdmEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDeputyDirAdmEmployees($searchTerm);

        return $employees;
    }

    public function deputyDirOpEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDeputyDirOpEmployees($searchTerm);

        return $employees;
    }

    public function dirSecEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findDirSecEmployees($searchTerm);

        return $employees;
    }

    public function instEmployees(Request $request)
    {
        $searchTerm = $request->get('term');
        $employees = $this->employeeManager->findInstEmployees($searchTerm);

        return $employees;
    }

}
