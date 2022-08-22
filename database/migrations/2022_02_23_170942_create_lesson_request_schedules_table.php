<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonRequestSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_request_schedules', function (Blueprint $table) {
            $table->increments('lrs_id');
            $table->unsignedInteger('lrs_lr_id')->comment('lesson_request\'s lr_id');
            $table->date('lrs_date');
            $table->time('lrs_start_time');
            $table->time('lrs_end_time');
            $table->tinyInteger('lrs_state')->comment('0: kouhai request, 1: kouhai cancel, 2: senpai confirm, 3: senpai cancel');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_request_schedules');
    }
}
