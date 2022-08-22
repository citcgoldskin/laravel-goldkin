<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnLessonRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('lesson_requests', function (Blueprint $table){
            $table->integer('lr_amount')->after('lr_until_confirm')->comment('レッスン料');
            $table->tinyInteger('lr_fee_type')->after('lr_amount')->comment('手数料率, 0: A, 1: B, 2: C');
            $table->integer('lr_fee')->after('lr_fee_type')->comment('手数料');
            $table->integer('lr_service_fee')->after('lr_fee')->comment('サービス料');
            $table->renameColumn('ar_hope_mintime', 'lr_hope_mintime');
            $table->renameColumn('ar_hope_maxtime', 'lr_hope_maxtime');
            $table->renameColumn('ar_area_id', 'lr_area_id');
            $table->renameColumn('ar_address', 'lr_address');
            $table->renameColumn('ar_address_detail', 'lr_address_detail');
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
