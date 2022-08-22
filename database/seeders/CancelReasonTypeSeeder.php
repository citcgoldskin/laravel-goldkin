<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class CancelReasonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cancel_reason_types')->truncate();
        $sql_path = 'database/sql/cancel_reason_types.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('cancel_reason_types table seeded!');
    }
}
