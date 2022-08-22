<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class LessonClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lesson_classes')->truncate();
        $sql_path = 'database/sql/lesson_classes.sql';
        DB::unprepared(file_get_contents($sql_path));
        $this->command->info('Lesson_classes table seeded!');
    }
}
