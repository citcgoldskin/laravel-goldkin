<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUnnecessaryColumnsFromCardCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_credits', function (Blueprint $table) {
            $table->dropColumn('cc_company_id');
            $table->dropColumn('cc_card_brand');
            $table->dropColumn('cc_number');
            $table->dropColumn('cc_validation_date');
            $table->dropColumn('cc_client_name');
            $table->dropColumn('cc_security_code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_credits', function (Blueprint $table) {
            //
        });
    }
}
