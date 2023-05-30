<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/20/20
 * Time: 11:13 AM
 */

namespace App\Managers\Secdbms\Ims;


use App\Contracts\Secdbms\Ims\OtherInfoContract;
use App\Entities\Secdbms\Ims\ImsAttachment;
use App\Entities\Secdbms\Ims\ImsContainerDetained;
use App\Entities\Secdbms\Ims\ImsCriminal;
use App\Entities\Secdbms\Ims\ImsDriver;
use App\Entities\Secdbms\Ims\ImsVehicleDetained;
use App\Entities\Secdbms\Ims\ImsVictim;
use App\Enums\Secdbms\Ims\OtherInfoType;

class OtherInfoManager implements OtherInfoContract
{
    public function findVictims($incidentId, $incidentActionId) {

        return ImsVictim::where(
            [
                ['other_info_type_id', OtherInfoType::VICTIM],
                ['incidence_id', $incidentId],
                ['incidence_actionid', $incidentActionId]

            ]
        )->get();
    }

    public function markVictim($count) {
        return ($count > 0) ? OtherInfoType::VICTIM : null;
    }

    public function findDrivers($incidentId, $incidentActionId) {

        return ImsDriver::where(
            [
                ['other_info_type_id', OtherInfoType::DRIVER],
                ['incidence_id', $incidentId],
                ['incidence_actionid', $incidentActionId]

            ]
        )->get();
    }

    public function markDriver($count) {
        return ($count > 0) ? OtherInfoType::DRIVER : null;
    }

    public function findCriminals($incidentId, $incidentActionId) {

        return ImsCriminal::where(
            [
                ['other_info_type_id', OtherInfoType::CRIMINAL],
                ['incidence_id', $incidentId],
                ['incidence_actionid', $incidentActionId]

            ]
        )->get();
    }

    public function markCriminal($count) {
        return ($count > 0) ? OtherInfoType::CRIMINAL : null;
    }

    public function findVehicles($incidentId, $incidentActionId) {

        return ImsVehicleDetained::where(
            [
                ['other_info_type_id', OtherInfoType::VEHICLE],
                ['incidence_id', $incidentId],
                ['incidence_action_id', $incidentActionId]

            ]
        )->get();
    }

    public function markVehicle($count) {
        return ($count > 0) ? OtherInfoType::VEHICLE : null;
    }

    public function findContainers($incidentId, $incidentActionId) {

        return ImsContainerDetained::where(
            [
                ['other_info_type_id', OtherInfoType::CONTAINER],
                ['incidence_id', $incidentId],
                ['incidence_action_id', $incidentActionId]

            ]
        )->get();
    }

    public function markContainer($count) {
        return ($count > 0) ? OtherInfoType::CONTAINER : null;
    }

    public function hasFirDetails($incidenceAction)
    {
        if(($incidenceAction->fir_number) && ($incidenceAction->fir_date)) {
            return true;
        }

        return false;
    }

    public function findAttachments($incidentId, $incidentActionId) {
        return ImsAttachment::where(
            [
                ['incidence_id', $incidentId],
                ['incidence_action_id', $incidentActionId]

            ]
        )->get();
    }

    public function hasAttachment($count) {
        return $count > 0;
    }

    public function collectFiles($files, $key) {
        $uploadedFiles = [];

        if($files) {
            foreach($files as $index => $file) {
                foreach($file as $i => $f) {
                    if($f[$key]) {
                        $uploadedFiles[$index][$i][$key] = $f[$key];
                    }
                }
            }
        }

        return $uploadedFiles;
    }
}