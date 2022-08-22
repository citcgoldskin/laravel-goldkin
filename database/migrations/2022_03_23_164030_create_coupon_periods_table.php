<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_periods', function (Blueprint $table) {
            $table->increments('cpp_id');
            $table->integer('cpp_user_id');
            $table->integer('cpp_cup_id');
            $table->date('cpp_start_date');
            $table->date('cpp_end_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupon_periods');
    }
}
