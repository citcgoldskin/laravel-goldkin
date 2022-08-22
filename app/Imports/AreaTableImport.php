<?php

namespace App\Imports;

use Carbon\Carbon;
use League\Csv\Reader;
use Auth;
use DB;

class AreaTableImport
{

    public static function run($file)
    {
        ini_set('max_execution_time', 10800);


        $csv = Reader::createFromPath($file);

        $created_count = 0;
        foreach ($csv as $key => $row) {

            if($key == 0) {
                continue;
            }

            if(empty(trim($row[0]))) {
                continue;
            }

            mb_convert_variables('UTF-8', 'SJIS-win', $row);

            $record = [
                'area_id' => $key,
                'area_name' => trim($row[0]),
                'area_code' => trim($row[1]),
                'area_deep' => trim($row[2]),
                'area_region' => trim($row[3]),
                'area_pref' => trim($row[4]),
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s')
            ];

            DB::table('areas')->insert($record);

            echo trim($row[0]) . '-' . trim($row[1]) . "\n";

            $created_count++;
        }

        return  $created_count;
    }
}
