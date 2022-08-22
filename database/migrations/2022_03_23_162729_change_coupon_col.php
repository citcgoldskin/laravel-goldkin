<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCouponCol extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('coupons', function (Blueprint $table){
            $table->dropColumn('cup_datetime_start');
            $table->dropColumn('cup_datetime_end');
            $table->tinyInteger('cup_period')->after('cup_sell_money');
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
