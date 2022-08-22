<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMsgMcId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('msgs', function (Blueprint $table) {
            $table->tinyInteger('msg_mc_id')->after('msg_to_user_id');
            $table->string('msg_mt_code', 50)->nullable()->change();
            $table->dropColumn('msg_mt_name');
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
