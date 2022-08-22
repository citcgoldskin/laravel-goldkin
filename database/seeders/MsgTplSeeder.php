<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class MsgTplSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('msg_tpls')->truncate();
        $sql_path = 'database/sql/msg_tpls.sql';
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('msg_tpls table seeded!');
    }
}
