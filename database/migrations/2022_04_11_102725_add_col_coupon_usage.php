<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColCouponUsage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('coupon_usages', function (Blueprint $table) {
            $table->date('cpu_date_to')->nullable()->change();
            $table->date('cpu_date_get')->after('cpu_lrs_id');
            $table->tinyInteger('cpu_order')->after('cpu_date_to');
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
