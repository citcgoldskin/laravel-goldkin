<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveRequestScheduleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->dropColumn('lrs_man_num');
            $table->dropColumn('lrs_woman_num');
            $table->dropColumn('lrs_area_id');
            $table->dropColumn('lrs_address');
            $table->dropColumn('lrs_address_detail');

            $table->integer('lrs_state')->default(0)->comment('0:request,1:confirm,2:reserve,3:start,4:complete,5:cnacel_senpai,6:cancel_kouhai,7:cancel_system')->change();
            $table->date('lrs_until_buy')->nullable()->after('lrs_traffic_fee');
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
