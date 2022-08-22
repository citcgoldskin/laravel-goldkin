<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class EvalutionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $sql_path = 'database/sql/evalution_types.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('evalution_types table seeded!');
    }
}
