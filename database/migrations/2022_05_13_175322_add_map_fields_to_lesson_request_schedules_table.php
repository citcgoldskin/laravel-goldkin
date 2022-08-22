<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMapFieldsToLessonRequestSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->tinyInteger('lrs_kouhai_is_share_position')->after('lrs_old_schedule')->comment('位置情報を共有');
            $table->integer('lrs_kouhai_id')->after('lrs_old_schedule')->comment('コウハイID');
            $table->tinyInteger('lrs_senpai_is_share_position')->after('lrs_old_schedule')->comment('位置情報を共有');
            $table->integer('lrs_senpai_id')->after('lrs_old_schedule')->comment('センパイID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            //
        });
    }
}
