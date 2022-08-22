<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DelColCouponUsagesCode extends Migration
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
            $table->dropColumn('cpu_code');
            $table->dropColumn('cpu_kind');
            $table->dropColumn('cpu_number');
            $table->date('cpu_date_to')->after('cpu_number');
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
