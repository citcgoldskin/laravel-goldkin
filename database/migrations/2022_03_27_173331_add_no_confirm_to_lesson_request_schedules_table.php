<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNoConfirmToLessonRequestSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->integer('lrs_state')->comment('0-kouhai req,1-kouhai cancel,2-senpai confirm,3:senpai cancel,4:complete,5:modified')->change();
            $table->tinyInteger('lrs_no_confirm')->after('lrs_cancel_fee')->comment('0:old request,1:cancel request');
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
