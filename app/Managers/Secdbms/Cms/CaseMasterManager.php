<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/20/20
 * Time: 11:13 AM
 */

namespace App\Managers\Secdbms\Cms;


use App\Contracts\Secdbms\Cms\CaseMasterContract;
use App\Entities\Pmis\Employee\Employee;
use App\Entities\Secdbms\Cms\CaseMasterInfo;
use App\Entities\Secdbms\Cms\CourtMasterInfo;
use App\Enums\Pmis\Employee\Statuses;
use Illuminate\Support\Facades\DB;
use PDO;


class CaseMasterManager implements CaseMasterContract
{
    public function storeCaseCategoryInfo($request = [])
    {
        $category_id = '';
        if($request->get("category_id"))
        {
            $category_id = $request->get("category_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_category_id" => $category_id,
                "p_category_name" => $request->get("category_name"),
                "p_category_name_bng" => $request->get("category_name_bng"),
                "p_active_yn" => $request->get("active_yn"),
                "p_description" => $request->get("cat_desc"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.CASE_CATEGORY_CREATION", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
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

    public function storeRateChartInfo($request = [])
    {
        $rate_chart_id = '';
        if($request->get("rate_chart_id"))
        {
            $rate_chart_id = $request->get("rate_chart_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $params = [
                "p_rate_chart_id" => $rate_chart_id,
                "p_assignment_type_id" => $request->get("service_name"),
                "p_minimum_rate" => $request->get("rate_from"),
                "p_maximum_rate" => $request->get("rate_to"),
                "p_active_yn" => $request->get("active_yn"),
                "p_active_from" => $request->get("active_from"),
                "p_active_to" => $request->get("active_to"),
                "p_rate_description" => $request->get("description"),
                "p_insert_by" => auth()->id(),
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message,
            ];
            DB::executeProcedure("CPACMS.case_rate_chart_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
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

    public function storeCaseInfo($request = [])
    {
        $case_id = '';
        if($request->get("case_id"))
        {
            $case_id = $request->get("case_id");
        }

        try {
            $status_code = sprintf("%4000s", "");
            $status_message = sprintf("%4000s", "");
            $case_id_get = sprintf("%4000s", "");
            $params1 = [
                "p_case_id" => $case_id,
                "p_case_no" => $request->get("case_no"),
                "p_case_date" => $request->get("case_date"),
                "p_case_description" => $request->get("description"),
                "p_category_id" => $request->get("case_category"),
                "p_court_id" => $request->get("court_id"),
                "p_case_status_id" => $request->get("case_status"),
                "p_insert_by" => auth()->id(),
                "o_case_id" => &$case_id_get,
                "o_status_code" => &$status_code,
                "o_status_message" => &$status_message
            ];
            DB::executeProcedure("CPACMS.case_info_creation", $params1);
        } catch (\Exception $e) {
            $params1 = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }

        return $params1;

    }

    public function storeCaseInfoCompDef($request = [], $case_id)
    {
        $party_details_id = '';
        try {
                if($request->get("comp_name"))
                {
                    if($request->get("comp_party_details_id"))
                    {

                            $comp_name = count($request->get('comp_name'));
                            $comp_party_details_id = $request->get('comp_party_details_id');
                            for ($x = 1; $x <= $comp_name; $x++)
                            {
                                $array[] = $x;
                                array_push($comp_party_details_id, '0');
                            }


                        foreach($request->get('comp_name') as $indx => $value)
                        {

                            $status_code = sprintf("%4000s", "");
                            $status_message = sprintf("%4000s", "");
                            $params1 = [
                                "p_party_details_id" => $comp_party_details_id[$indx],
                                "p_party_details_name" => $request->get('comp_name')[$indx],
                                "p_party_details_name_bng" => '',
                                "p_party_details_address" => $request->get('comp_address')[$indx],
                                "p_party_details_address_bng" => '',
                                "p_party_contact_no" => $request->get('comp_contact_no')[$indx],
                                "p_organisation_yn" => '',
                                "p_party_id" => '1',
                                "p_case_id" => $case_id,
                                "p_insert_by" => auth()->id(),
                                "o_status_code" => &$status_code,
                                "o_status_message" => &$status_message
                            ];
                            DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                        }
                    }
                    else
                    {
                        foreach($request->get('comp_name') as $indx => $value)
                        {

                            $status_code = sprintf("%4000s", "");
                            $status_message = sprintf("%4000s", "");
                            $params1 = [
                                "p_party_details_id" => $party_details_id,
                                "p_party_details_name" => $request->get('comp_name')[$indx],
                                "p_party_details_name_bng" => '',
                                "p_party_details_address" => $request->get('comp_address')[$indx],
                                "p_party_details_address_bng" => '',
                                "p_party_contact_no" => $request->get('comp_contact_no')[$indx],
                                "p_organisation_yn" => '',
                                "p_party_id" => '1',
                                "p_case_id" => $case_id,
                                "p_insert_by" => auth()->id(),
                                "o_status_code" => &$status_code,
                                "o_status_message" => &$status_message
                            ];
                            DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                        }
                    }
                }

            if($request->get("def_name"))
            {
                if($request->get("def_party_details_id")) {
                    $def_name = count($request->get('def_name'));
                    $def_party_details_id = $request->get('def_party_details_id');
                    for ($x = 1; $x <= $def_name; $x++)
                    {
                        $array[] = $x;
                        array_push($def_party_details_id, '0');
                    }

                    foreach ($request->get('def_name') as $indx => $value) {

                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params1 = [
                            "p_party_details_id" => $def_party_details_id[$indx],
                            "p_party_details_name" => $request->get('def_name')[$indx],
                            "p_party_details_name_bng" => '',
                            "p_party_details_address" => $request->get('def_address')[$indx],
                            "p_party_details_address_bng" => '',
                            "p_party_contact_no" => $request->get('def_contact_no')[$indx],
                            "p_organisation_yn" => '',
                            "p_party_id" => '2',
                            "p_case_id" => $case_id,
                            "p_insert_by" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];
                        DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                    }
                }
                else
                {
                    foreach ($request->get('def_name') as $indx => $value) {

                        $status_code = sprintf("%4000s", "");
                        $status_message = sprintf("%4000s", "");
                        $params1 = [
                            "p_party_details_id" => $party_details_id,
                            "p_party_details_name" => $request->get('def_name')[$indx],
                            "p_party_details_name_bng" => '',
                            "p_party_details_address" => $request->get('def_address')[$indx],
                            "p_party_details_address_bng" => '',
                            "p_party_contact_no" => $request->get('def_contact_no')[$indx],
                            "p_organisation_yn" => '',
                            "p_party_id" => '2',
                            "p_case_id" => $case_id,
                            "p_insert_by" => auth()->id(),
                            "o_status_code" => &$status_code,
                            "o_status_message" => &$status_message
                        ];
                        DB::executeProcedure("CPACMS.case_party_details_creation", $params1);
                    }
                }
            }

        } catch (\Exception $e) {
        }
        return $params1;
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

    public function storeCaseFile($request = [], $case_id)
    {
        $case_doc_id = '';
        if($request->get("case_doc_id"))
        {
            $case_doc_id = $request->get("case_doc_id");
        }

        $attachedFileList = '';
        $attachedFileListOld = '';
        if($request->file('attachedFileList'))
        {
            $attachedFileList = $request->file('attachedFileList');
            foreach ($attachedFileList as $indx => $value)
            {
                $attachedFileName[$indx] = $attachedFileList[$indx]->getClientOriginalName();
                $attachedFileType[$indx] = $attachedFileList[$indx]->getMimeType();
                $attachedFileContent[$indx] = base64_encode(file_get_contents($attachedFileList[$indx]->getRealPath()));
            }
        }

        if($request->file('attachedFileListOld'))
        {
            $attachedFileListOld = $request->file('attachedFileListOld');
            foreach ($attachedFileListOld as $indx => $value)
            {
                $attachedFileNameOld[$indx] = $attachedFileListOld[$indx]->getClientOriginalName();
                $attachedFileTypeOld[$indx] = $attachedFileListOld[$indx]->getMimeType();
                $attachedFileContentOld[$indx] = base64_encode(file_get_contents($attachedFileListOld[$indx]->getRealPath()));
            }
        }

        if($attachedFileListOld)
        {
            foreach ($attachedFileNameOld as $indx => $value)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_case_doc_id" => $request->get('case_doc_id')[$indx],
                    "p_case_doc_name" => $attachedFileNameOld[$indx],
                    "p_case_doc_name_bng" => '',
                    "p_case_doc" => ['value' => $attachedFileContentOld[$indx], 'type' => PDO::PARAM_LOB],
                    "p_case_doc_type" => $attachedFileTypeOld[$indx],
                    "p_case_id" => $case_id,
                    "p_initial_yn" => 'Y',
                    "p_case_update_id" => '',
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("CPACMS.case_documents_store", $params);
            }
        }

        if($attachedFileList)
        {
            foreach ($attachedFileName as $indx => $value)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params1 = [
                    "p_case_doc_id" => $case_doc_id,
                    "p_case_doc_name" => $attachedFileName[$indx],
                    "p_case_doc_name_bng" => '',
                    "p_case_doc" => ['value' => $attachedFileContent[$indx], 'type' => PDO::PARAM_LOB],
                    "p_case_doc_type" => $attachedFileType[$indx],
                    "p_case_id" => $case_id,
                    "p_initial_yn" => 'Y',
                    "p_case_update_id" => '',
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("CPACMS.case_documents_store", $params1);
            }
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
            ];
            DB::executeProcedure("CPACMS.bill_master_info_creation", $params);
        } catch (\Exception $e) {
            $params = ["status" => false, "o_status_code" => 99, "o_status_message" => $e->getMessage()];
        }
        return $params;
    }

    public function storeCaseUpdateInfo($request = [])
    {
        $case_update_id = '';
        if($request->get("case_update_id"))
        {
            $case_update_id = $request->get("case_update_id");
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

    public function storeCaseUpdateFile($request = [], $case_update_id)
    {
        $case_doc_id = '';
        if($request->get("case_doc_id"))
        {
            $case_doc_id = $request->get("case_doc_id");
        }

        $attachedFileList = '';
        $attachedFileListOld = '';
        if($request->file('attachedFileList'))
        {
            $attachedFileList = $request->file('attachedFileList');
            foreach ($attachedFileList as $indx => $value)
            {
                $attachedFileName[$indx] = $attachedFileList[$indx]->getClientOriginalName();
                $attachedFileType[$indx] = $attachedFileList[$indx]->getMimeType();
                $attachedFileContent[$indx] = base64_encode(file_get_contents($attachedFileList[$indx]->getRealPath()));
            }
        }

        if($request->file('attachedFileListOld'))
        {
            $attachedFileListOld = $request->file('attachedFileListOld');
            foreach ($attachedFileListOld as $indx => $value)
            {
                $attachedFileNameOld[$indx] = $attachedFileListOld[$indx]->getClientOriginalName();
                $attachedFileTypeOld[$indx] = $attachedFileListOld[$indx]->getMimeType();
                $attachedFileContentOld[$indx] = base64_encode(file_get_contents($attachedFileListOld[$indx]->getRealPath()));
            }
        }

        if($attachedFileListOld)
        {
            foreach ($attachedFileNameOld as $indx => $value)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params = [
                    "p_case_doc_id" => $request->get('case_doc_id')[$indx],
                    "p_case_doc_name" => $attachedFileNameOld[$indx],
                    "p_case_doc_name_bng" => '',
                    "p_case_doc" => ['value' => $attachedFileContentOld[$indx], 'type' => PDO::PARAM_LOB],
                    "p_case_doc_type" => $attachedFileTypeOld[$indx],
                    "p_case_id" => $request->get('case_no'),
                    "p_initial_yn" => 'N',
                    "p_case_update_id" => $case_update_id,
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("CPACMS.case_documents_store", $params);
            }
        }

        if($attachedFileList)
        {
            foreach ($attachedFileName as $indx => $value)
            {
                $status_code = sprintf("%4000s", "");
                $status_message = sprintf("%4000s", "");
                $params1 = [
                    "p_case_doc_id" => $case_doc_id,
                    "p_case_doc_name" => $attachedFileName[$indx],
                    "p_case_doc_name_bng" => '',
                    "p_case_doc" => ['value' => $attachedFileContent[$indx], 'type' => PDO::PARAM_LOB],
                    "p_case_doc_type" => $attachedFileType[$indx],
                    "p_case_id" =>  $request->get('case_no'),
                    "p_initial_yn" => 'N',
                    "p_case_update_id" => $case_update_id,
                    "p_insert_by" => auth()->id(),
                    "o_status_code" => &$status_code,
                    "o_status_message" => &$status_message
                ];
                DB::executeProcedure("CPACMS.case_documents_store", $params1);
            }
        }
    }

    public function findEmployeeCodesBy($searchTerm)
    {
        return Employee::where(
            [
                ['emp_code', 'like', ''.$searchTerm.'%'],
                ['emp_status_id', '=', Statuses::ON_ROLE],
            ]
        )->orderBy('emp_code', 'ASC')->limit(10)->get(['emp_id', 'emp_code', 'emp_name']);

    }

    public function findEmployeeInformation($employeeId)
    {
        $querys = "SELECT CPACMS.employee_data_list('".$employeeId."') from dual" ;
        $employee = DB::selectOne(DB::raw($querys));

        if($employee)
        {
            $jsonEncodedEmployee = json_encode($employee);
            $employeeArray = json_decode($jsonEncodedEmployee, true);
            return $employeeArray;
        }

        return [];
    }

    public function findCaseIdBy($searchTerm)
    {
       /* return CaseMasterInfo::where(
            [
                ['case_no', 'like', ''.$searchTerm.'%']
                //['case_no', ''.$searchTerm.'%']
            ]
        )->orderBy('case_id', 'ASC')->limit(10)->get(['case_id', 'case_no']);*/
        return CaseMasterInfo::where(DB::raw('upper(case_no)'), 'LIKE', ''.strtoupper($searchTerm).'%')->orderBy('case_id', 'ASC')->limit(10)->get(['case_id', 'case_no']);

    }

    public function findCaseIdInformation($employeeId)
    {
        $querys = "SELECT CPACMS.employee_data_list('".$employeeId."') from dual" ;
        $employee = DB::selectOne(DB::raw($querys));

        if($employee)
        {
            $jsonEncodedEmployee = json_encode($employee);
            $employeeArray = json_decode($jsonEncodedEmployee, true);
            return $employeeArray;
        }

        return [];
    }


    public function sendSMS($sender_no, $msg_bdy, $trace_code)
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
