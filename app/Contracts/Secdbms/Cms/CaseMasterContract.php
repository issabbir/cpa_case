<?php


namespace App\Contracts\Secdbms\Cms;


interface CaseMasterContract
{
    public function storeCaseCategoryInfo($request);
    public function storeCourtInfo($request);
    public function findEmployeeCodesBy($searchTerm);
    public function findEmployeeInformation($employeeId);
    public function storeRateChartInfo($request);

    public function storeLawyerInfo($request);
    public function storeLawyerCourtInfoDtl($request, $lawyer_id);
    public function storeLawyerCourtInfoDtlUpdt($request, $lawyer_id);

    public function removePartyDataInfo($request);
    public function removeCaseDoc($request);
    public function storeCaseFile($request, $case_id);
    public function storeCaseInfo($request);
    public function storeCaseInfoCompDef($request, $case_id);

    public function storeLawyerAssignInfo($request);
    public function storeCaseUpdateInfo($request);
    public function sendSMS($sender_no, $msg_bdy, $trace_code);
    public function storeCaseUpdateFile($request, $case_update_id);
    public function storeLawyerBillInfo($request);
}
