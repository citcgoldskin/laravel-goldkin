<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_path = 'database/sql/questiones.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('questiones table seeded!');
    }
}
