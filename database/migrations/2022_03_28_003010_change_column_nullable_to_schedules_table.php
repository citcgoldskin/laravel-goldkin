<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnNullableToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->integer('lrs_man_num')->nullable()->change();
            $table->integer('lrs_woman_num')->nullable()->change();
            $table->integer('lrs_area_id')->nullable()->change();
            $table->integer('lrs_address')->nullable()->change();
            $table->integer('lrs_address_detail')->nullable()->change();
            $table->integer('lrs_no_confirm')->nullable()->change();
            $table->integer('lrs_old_schedule')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
        });
    }
}
