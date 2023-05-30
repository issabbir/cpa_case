<?php
/**
 * Created by PhpStorm.
 * User: roman
 * Date: 4/19/20
 * Time: 10:47 AM
 */

namespace App\Enums\Secdbms\Ims;


class IncidenceStatus
{
    public const NEW = 1;
    public const ASSIGNED = 2;
    public const INVESTIGATION = 3;
    public const REPORT_SUBMIT = 4;
    public const ACTION_TAKEN = 5;
    public const COMPLETED = 6;
}