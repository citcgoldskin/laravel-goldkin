<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AppealClassSeeder::class,
            AreaSeeder::class,
            CancelReasonTypeSeeder::class,
            EvalutionTypeSeeder::class,
            LessonClassSeeder::class,
            LessonConditionSeeder::class,
            MsgClassSeeder::class,
            MsgTplSeeder::class,
            QuestionClassSeeder::class,
            /*QuestionSeeder::class,*/
            UserSeeder::class,
            CardCompany::class,
            SettingSeeder::class,
            BankSeeder::class
        ]);
    }
}
