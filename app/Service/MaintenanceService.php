<?php

namespace App\Service;

use App\Models\Maintenance;
use Auth;
use DB;
use Storage;

class MaintenanceService
{
    public static function doSearch() {
        return Maintenance::orderByDesc('updated_at');
    }

    public static function doCreate($data)
    {
        return Maintenance::create($data);
    }

    public static function doUpdate(Maintenance $obj_maintenance,  $data)
    {
        if($obj_maintenance) {
            return $obj_maintenance->update($data);
        }
        return false;
    }

}
