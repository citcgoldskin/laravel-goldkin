<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class CardCompany extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_path = 'database/sql/card_companies.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('card_companies table seeded!');

    }
}
