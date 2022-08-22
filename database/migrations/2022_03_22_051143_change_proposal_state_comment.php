<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeProposalStateComment extends Migration
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
            $table->dropColumn('pro_state');
        });

        Schema::table('proposals', function (Blueprint $table) {
            $table->tinyInteger('pro_state')->after('pro_buy_datetime')->comment('0: request, 1: ing, 2: complete');
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
