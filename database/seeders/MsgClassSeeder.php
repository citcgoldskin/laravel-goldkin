<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class MsgClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_path = 'database/sql/msg_classes.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('msg_classes table seeded!');
    }
}
