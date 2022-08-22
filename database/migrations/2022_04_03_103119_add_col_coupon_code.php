<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColCouponCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('coupons', function (Blueprint $table) {
            $table->dropColumn('cup_cnt_now');
            $table->dropColumn('cup_date_to');
            $table->tinyInteger('cup_visible')->comment('0 : visible, 1: invisible')->default(0)->after('cup_period');
            $table->string('cup_code',255)->after('cup_from_user_id');
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
