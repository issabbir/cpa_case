<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/13/20
 * Time: 5:14 PM
 */

namespace App\Managers\Secdbms\Ims;


use App\Contracts\Secdbms\Ims\ImsIncidenceContract;
use App\Entities\Secdbms\Ims\ImsIncidence;
use App\Enums\Secdbms\Ims\IncidenceStatus;
use App\Enums\YesNoFlag;
use Illuminate\Support\Facades\DB;

class ImsIncidenceManager implements ImsIncidenceContract
{
    public function findNew()
    {
        $query = <<<QUERY
            SELECT ii.incidence_id incidence_id,
       ii.incidence_number incidence_number,
       (CASE WHEN ii.REPORTER_EMP_ID IS NOT NULL THEN (e.emp_name || ' (' || e.emp_code || ')') ELSE ii.reporter_name END) reporter_name,
       ii.reporter_mobile reporter_mobile,
       iit.incidence_type incidence_type,
       (CASE WHEN ii.OTHER_INCIDENCE IS NOT NULL THEN ii.OTHER_INCIDENCE ELSE iis.INCIDENCE_SUBTYPE_NAME END) incidence,
       (to_char(ii.incidence_date, 'DD-MM-YYYY') || ' ' || to_char(ii.incidence_time, 'HH:MI AM')) incidence_on,
       (iip.PLACE_NAME || ' - ' || ii.INCIDENCE_PLACE) place,
       ist.incidence_status incidence_status
FROM IMS_INCIDENCE ii
       LEFT JOIN IMS_INCIDENCE_TYPE iit ON ii.INCIDENCE_TYPE_ID = iit.INCIDENCE_TYPE_ID
       LEFT JOIN IMS_INCIDENCE_SUBTYPE iis ON ii.INCIDENCE_SUBTYPE_ID = iis.INCIDENCE_SUBTYPE_ID
       LEFT JOIN IMS_INCIDENCE_PLACE iip ON ii.INCIDENCE_PLACE_ID = iip.INCIDENCE_PLACE_ID
       LEFT JOIN pmis.employee e ON ii.REPORTER_EMP_ID IS NOT NULL AND ii.REPORTER_EMP_ID = e.emp_id
       LEFT JOIN IMS_INCIDENCE_STATUS ist ON ii.INCIDENCE_STATUS_ID = ist.INCIDENCE_STATUS_ID
WHERE  ii.INCIDENCE_STATUS_ID = :status_id
QUERY;

        return DB::select($query, ['status_id' => IncidenceStatus::NEW]);
    }

    public function findByAssignedEmployee($employeeId, $status)
    {
        $query = <<<QUERY
            SELECT ii.incidence_id incidence_id,
               ii.incidence_number incidence_number,
               (CASE WHEN ii.REPORTER_EMP_ID IS NOT NULL THEN (e.emp_name || ' (' || e.emp_code || ')') ELSE ii.reporter_name END) reporter_name,
               ii.reporter_mobile reporter_mobile,
               iit.incidence_type incidence_type,
               (CASE WHEN ii.OTHER_INCIDENCE IS NOT NULL THEN ii.OTHER_INCIDENCE ELSE iis.INCIDENCE_SUBTYPE_NAME END) incidence,
               (to_char(ii.incidence_date, 'DD-MM-YYYY') || ' ' || to_char(ii.incidence_time, 'HH:MI AM')) incidence_on,
               (iip.PLACE_NAME || ' - ' || ii.INCIDENCE_PLACE) place,
               to_char(ii.assign_due_date, 'DD-MM-YYYY') assign_due_date
            FROM IMS_INCIDENCE ii
                        LEFT JOIN IMS_INCIDENCE_TYPE iit ON ii.INCIDENCE_TYPE_ID = iit.INCIDENCE_TYPE_ID
                        LEFT JOIN IMS_INCIDENCE_SUBTYPE iis ON ii.INCIDENCE_SUBTYPE_ID = iis.INCIDENCE_SUBTYPE_ID
                        LEFT JOIN IMS_INCIDENCE_PLACE iip ON ii.INCIDENCE_PLACE_ID = iip.INCIDENCE_PLACE_ID
                        LEFT JOIN pmis.employee e ON ii.REPORTER_EMP_ID IS NOT NULL AND ii.REPORTER_EMP_ID = e.emp_id
                        LEFT JOIN pmis.employee assigned_employee ON ii.ASSIGN_EMP_ID = assigned_employee.emp_id
            WHERE
                          assigned_employee.emp_id = :emp_id
                          AND ii.INCIDENCE_STATUS_ID = :status_id
QUERY;

        return DB::select($query, ['emp_id' => $employeeId, 'status_id' => $status]);
    }


    public function findByStatus($statusId)
    {
        $query = <<<QUERY
        SELECT ii.incidence_id incidence_id,
           ii.incidence_number incidence_number,
           (CASE WHEN ii.REPORTER_EMP_ID IS NOT NULL THEN (e.emp_name || ' (' || e.emp_code || ')') ELSE ii.reporter_name END) reporter_name,
           ii.reporter_mobile reporter_mobile,
           iit.incidence_type incidence_type,
           (CASE WHEN ii.OTHER_INCIDENCE IS NOT NULL THEN ii.OTHER_INCIDENCE ELSE iis.INCIDENCE_SUBTYPE_NAME END) incidence,
           (to_char(ii.incidence_date, 'DD-MM-YYYY') || ' ' || to_char(ii.incidence_time, 'HH:MI AM')) incidence_on,
           (iip.PLACE_NAME || ' - ' || ii.INCIDENCE_PLACE) place,
           ist.incidence_status incidence_status
        FROM IMS_INCIDENCE ii
            LEFT JOIN IMS_INCIDENCE_TYPE iit ON ii.INCIDENCE_TYPE_ID = iit.INCIDENCE_TYPE_ID
            LEFT JOIN IMS_INCIDENCE_SUBTYPE iis ON ii.INCIDENCE_SUBTYPE_ID = iis.INCIDENCE_SUBTYPE_ID
            LEFT JOIN IMS_INCIDENCE_PLACE iip ON ii.INCIDENCE_PLACE_ID = iip.INCIDENCE_PLACE_ID
            LEFT JOIN pmis.employee e ON ii.REPORTER_EMP_ID IS NOT NULL AND ii.REPORTER_EMP_ID = e.emp_id
            LEFT JOIN IMS_INCIDENCE_STATUS ist ON ii.INCIDENCE_STATUS_ID = ist.INCIDENCE_STATUS_ID
        WHERE
               ii.INCIDENCE_STATUS_ID = :status_id
QUERY;

        return DB::select($query, ['status_id' => $statusId]);
    }

    public function findOne($incidentId)
    {
        $query = <<<QUERY
        SELECT ii.incidence_id incidence_id,
           ii.incidence_number incidence_number,
           (CASE WHEN ii.REPORTER_CPA_YN='Y' THEN 'CPA' ELSE 'OUTSIDER' END) report_by,
           (CASE WHEN ii.REPORTER_EMP_ID IS NOT NULL THEN (e.emp_name || ' (' || e.emp_code || ')') ELSE ii.reporter_name END) reporter_name,
           ii.reporter_mobile reporter_mobile,
           ii.INCIDENCE_DESCRIPTION description,
           ii.INCIDENCE_DESCRIPTION_BN description_bn,
           iit.incidence_type incidence_type,
           (CASE WHEN ii.OTHER_INCIDENCE IS NOT NULL THEN ii.OTHER_INCIDENCE ELSE iis.INCIDENCE_SUBTYPE_NAME END) incidence,
           (to_char(ii.incidence_date, 'DD-MM-YYYY') || ' ' || to_char(ii.incidence_time, 'HH:MI AM')) incidence_on,
           (iip.PLACE_NAME || ' - ' || ii.INCIDENCE_PLACE) place,
           to_char(ii.ASSIGN_DUE_DATE, 'DD-MM-YYYY') due_date,
           iin.investigation_note note
        FROM IMS_INCIDENCE ii
            LEFT JOIN IMS_INCIDENCE_TYPE iit ON ii.INCIDENCE_TYPE_ID = iit.INCIDENCE_TYPE_ID
            LEFT JOIN IMS_INCIDENCE_SUBTYPE iis ON ii.INCIDENCE_SUBTYPE_ID = iis.INCIDENCE_SUBTYPE_ID
            LEFT JOIN IMS_INCIDENCE_PLACE iip ON ii.INCIDENCE_PLACE_ID = iip.INCIDENCE_PLACE_ID
            LEFT JOIN pmis.employee e ON ii.REPORTER_EMP_ID IS NOT NULL AND ii.REPORTER_EMP_ID = e.emp_id
            LEFT JOIN IMS_INCIDENCE_STATUS ist ON ii.INCIDENCE_STATUS_ID = ist.INCIDENCE_STATUS_ID
            LEFT JOIN IMS_INVESTIGATION iin ON ii.INCIDENCE_ID = iin.INCIDENCE_ID
        WHERE
               ii.incidence_id = :incidence_id
QUERY;

        return DB::selectOne($query, ['incidence_id' => $incidentId]);
    }

    public function findOtherInfo()
    {
        $query = <<<QUERY
        SELECT ii.incidence_id incidence_id,
           iia.incidence_action_id incidence_action_id,
           ii.incidence_number incidence_number,
           (CASE WHEN ii.REPORTER_EMP_ID IS NOT NULL THEN (e.emp_name || ' (' || e.emp_code || ')') ELSE ii.reporter_name END) reporter_name,
           ii.reporter_mobile reporter_mobile,
           iit.incidence_type incidence_type,
           (CASE WHEN ii.OTHER_INCIDENCE IS NOT NULL THEN ii.OTHER_INCIDENCE ELSE iis.INCIDENCE_SUBTYPE_NAME END) incidence,
           (to_char(ii.incidence_date, 'DD-MM-YYYY') || ' ' || to_char(ii.incidence_time, 'HH:MI AM')) incidence_on,
           (iip.PLACE_NAME || ' - ' || ii.INCIDENCE_PLACE) place,
           ist.incidence_status incidence_status
        FROM IMS_INCIDENCE ii
            LEFT JOIN IMS_INCIDENCE_TYPE iit ON ii.INCIDENCE_TYPE_ID = iit.INCIDENCE_TYPE_ID
            LEFT JOIN IMS_INCIDENCE_SUBTYPE iis ON ii.INCIDENCE_SUBTYPE_ID = iis.INCIDENCE_SUBTYPE_ID
            LEFT JOIN IMS_INCIDENCE_PLACE iip ON ii.INCIDENCE_PLACE_ID = iip.INCIDENCE_PLACE_ID
            LEFT JOIN pmis.employee e ON ii.REPORTER_EMP_ID IS NOT NULL AND ii.REPORTER_EMP_ID = e.emp_id
            LEFT JOIN IMS_INCIDENCE_STATUS ist ON ii.INCIDENCE_STATUS_ID = ist.INCIDENCE_STATUS_ID
            INNER JOIN IMS_INCIDENCE_ACTION iia ON ii.INCIDENCE_ID = iia.INCIDENCE_ID 
        WHERE ii.INCIDENCE_STATUS_ID = :action_taken OR ii.INCIDENCE_STATUS_ID = :completed
        ORDER BY ii.INCIDENCE_STATUS_ID
QUERY;

        return DB::select($query, ['action_taken' => IncidenceStatus::ACTION_TAKEN, 'completed' => IncidenceStatus::COMPLETED]);
    }
}