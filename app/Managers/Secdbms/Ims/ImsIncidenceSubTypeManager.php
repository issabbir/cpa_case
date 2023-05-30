<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/13/20
 * Time: 12:38 PM
 */

namespace App\Managers\Secdbms\Ims;


use App\Contracts\Secdbms\Ims\ImsIncidenceSubTypeContract;
use App\Entities\Secdbms\Ims\ImsIncidenceSubtype;

class ImsIncidenceSubTypeManager implements ImsIncidenceSubTypeContract
{
    public function findByType($incidentTypeId)
    {
        return ImsIncidenceSubtype::where('incidence_type_id', $incidentTypeId)->get();
    }
}