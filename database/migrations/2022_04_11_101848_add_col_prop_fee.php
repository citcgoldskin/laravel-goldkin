<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColPropFee extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('proposals', function (Blueprint $table) {
            $table->tinyInteger('pro_fee_type')->comment('手数料率, 0: A, 1: B, 2: C')->after('pro_money');
            $table->integer('pro_fee')->after('pro_fee_type');
            $table->integer('pro_service_fee')->after('pro_fee');
            $table->dateTime('pro_request_time')->nullable()->after('pro_buy_datetime');
            $table->dateTime('pro_proposing_time')->nullable()->after('pro_request_time');
            $table->dateTime('pro_complete_time')->nullable()->after('pro_proposing_time');
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
