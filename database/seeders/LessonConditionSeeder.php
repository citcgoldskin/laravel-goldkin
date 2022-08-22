<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class LessonConditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_path = 'database/sql/lesson_conditions.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('lesson_conditions table seeded!');
    }
}
