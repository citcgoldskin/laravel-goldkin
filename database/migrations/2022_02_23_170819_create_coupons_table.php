<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->increments('cup_id');
            $table->unsignedInteger('cup_from_user_id');
            $table->unsignedInteger('cup_to_user_id');
            $table->string('cup_name', 30);
            $table->integer('cup_apply_condition');
            $table->tinyInteger('cup_set_cnt');
            $table->integer('cup_reduce_money');
            $table->integer('cup_sell_money');
            $table->date('cup_datetime_start');
            $table->date('cup_datetime_end');
            $table->tinyInteger('cup_state')->comment('0-未用,1-已用,2-过期');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
