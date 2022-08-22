<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLessonRequestAndSchedules extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_requests', function (Blueprint $table){
            $table->dropColumn('lr_amount');
            $table->dropColumn('lr_fee_type');
            $table->dropColumn('lr_fee');
            $table->dropColumn('lr_service_fee');

        });

        Schema::table('lesson_request_schedules', function (Blueprint $table){
            $table->tinyInteger('lrs_fee_type')->after('lrs_amount')->comment('手数料率, 0: A, 1: B, 2: C');
            $table->integer('lrs_fee')->after('lrs_fee_type')->comment('手数料');
            $table->integer('lrs_service_fee')->after('lrs_fee')->comment('サービス料');
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
