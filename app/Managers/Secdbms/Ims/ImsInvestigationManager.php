<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/16/20
 * Time: 1:20 PM
 */

namespace App\Managers\Secdbms\Ims;


use App\Contracts\Secdbms\Ims\ImsInvestigationContract;
use App\Entities\Secdbms\Ims\ImsInvestigation;
use Illuminate\Support\Facades\DB;
use League\Flysystem\Config;

class ImsInvestigationManager implements ImsInvestigationContract
{
    public function findByIncident($incidentId)
    {
        return ImsInvestigation::where('incidence_id', $incidentId)->with(['witnesses'])->first();
    }
}