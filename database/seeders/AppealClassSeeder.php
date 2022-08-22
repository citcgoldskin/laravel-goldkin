<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class AppealClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('appeal_classes')->truncate();
        $sql_path = 'database/sql/appeal_classes.sql';
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('Appeal Class table seeded!');
        //
    }
}
