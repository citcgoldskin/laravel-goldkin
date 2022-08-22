<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->truncate();
        $sql_path = 'database/sql/settings.sql';
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('Setting Class table seeded!');
        //
    }
}
