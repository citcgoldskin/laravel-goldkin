<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class QuestionClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('question_classes')->truncate();
        $sql_path = 'database/sql/question_classes.sql';

        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('question_classes table seeded!');
    }
}
