<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
use Hash;

class AdminTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('admins')->truncate();
        DB::table('admins')->insert([
            [
                'id' => 1,
                'name' => '管理者',
                'login_id' => 'admin@senpai.inc',
                'password' =>Hash::make('12345678'),
            ]
        ]);
    }
}
