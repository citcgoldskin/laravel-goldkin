<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToLessonRequestSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->tinyInteger('lrs_man_num')->after('lrs_end_time');
            $table->tinyInteger('lrs_woman_num')->after('lrs_man_num');
            $table->tinyInteger('lrs_area_id')->after('lrs_woman_num');
            $table->tinyInteger('lrs_address')->after('lrs_area_id');
            $table->tinyInteger('lrs_address_detail')->after('lrs_address');
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
