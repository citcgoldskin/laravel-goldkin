<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStateDateColumnsToSchedule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->dateTime('lrs_request_date')->after('lrs_state');
            $table->dateTime('lrs_reverse_date')->after('lrs_state');
            $table->dateTime('lrs_complete_date')->after('lrs_state');
            $table->dateTime('lrs_cancel_date')->after('lrs_state');
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
