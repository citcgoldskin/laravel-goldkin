<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangePropStateComment extends Migration
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
            $table->tinyInteger('pro_state')->after('pro_buy_datetime')->comment('0:  draft,  1: request, 2: ing, 3: complete');
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
