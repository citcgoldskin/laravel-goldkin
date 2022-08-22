<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMsgSettingPushandmail2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('msg_settings', function (Blueprint $table) {
            $table->tinyInteger('ms_push')->after('ms_mc_id')->nullable()->comment('1: on, 0:off');
            $table->tinyInteger('ms_email')->after('ms_mc_id')->nullable()->comment('1: on, 0:off');
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
