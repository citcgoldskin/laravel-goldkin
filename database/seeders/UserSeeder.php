<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $sql_path = 'database/sql/users.sql';
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('users table seeded!');
    }
}
