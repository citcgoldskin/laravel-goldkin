<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_path = 'database/sql/areas.sql';
        DB::table('areas')->truncate();
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('Areas table seeded!');

    }
}
