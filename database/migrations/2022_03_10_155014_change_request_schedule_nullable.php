<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRequestScheduleNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->dateTime('lrs_request_date')->nullable()->change();
            $table->dateTime('lrs_reserve_date')->nullable()->change();
            $table->dateTime('lrs_complete_date')->nullable()->change();
            $table->dateTime('lrs_cancel_date')->nullable()->change();
            $table->string('lrs_cancel_reason')->nullable()->change();
            $table->string('lrs_cancel_note')->nullable()->change();
            $table->integer('lrs_cancel_fee')->nullable()->after('lrs_cancel_note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
