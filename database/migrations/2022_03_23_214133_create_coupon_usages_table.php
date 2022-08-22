<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponUsagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_usages', function (Blueprint $table) {
            $table->increments('cpu_id');
            $table->integer('cpu_cup_id');
            $table->integer('cpu_user_id');
            $table->tinyInteger('cpu_kind')->default(0)->comment('0: lesson schedule');
            $table->integer('cpu_use_id')->comment('cpu_kind ==0 ? lesson_schedule_id');
            $table->integer('cpu_number')->default(1);
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
        Schema::dropIfExists('coupon_usages');
    }
}
