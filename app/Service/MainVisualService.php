<?php

namespace App\Service;

use App\Models\MainVisual;
use Auth;
use DB;
use Storage;

class MainVisualService
{
    public static function doSearch($type) {
        return MainVisual::where('type', $type)
            ->orderBy('id');
    }

    public static function doCreate($data)
    {
        $obj_visual = MainVisual::create($data);
        if($obj_visual) {
            self::saveImageFile($obj_visual);
            return $obj_visual;
        }
        return false;
    }

    public static function doUpdate(MainVisual $obj_visual,  $data)
    {
        if($obj_visual) {
            $obj = $obj_visual->update($data);
            self::saveImageFile($obj_visual);
            return $obj;
        }
        return false;
    }

    public static function saveImageFile(MainVisual $obj_visual) {
        if(isset($obj_visual->file_path) && Storage::disk('temp')->exists($obj_visual->file_path)){
            $image_file = Storage::disk('temp')->get($obj_visual->file_path);
            Storage::disk('main_visual')->put($obj_visual->type.'/'.$obj_visual->file_path, $image_file);
            Storage::disk('temp')->delete($obj_visual->file_path);
        }
    }

}
