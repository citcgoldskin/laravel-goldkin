<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCouponsColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('coupon_periods');
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('cup_to_user_id');
            $table->renameColumn('cup_set_cnt', 'cup_cnt_origin');
        });

        Schema::table('coupons', function (Blueprint $table) {
            $table->integer('cup_cnt_now')->after('cup_cnt_origin');
            $table->dateTime('cup_date_from')->nullable()->after('cup_period');
            $table->dateTime('cup_date_to')->nullable()->after('cup_date_from');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //Schema::dropIfExists('coupon_periods');
    }
}
