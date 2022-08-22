<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeMsgOpen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('msgs', function (Blueprint $table) {
            $table->string('msg_content', 255)->after('msg_mt_name')->nullable();
            $table->tinyInteger('msg_open')->after('msg_mt_name')->default(0);
            $table->dropColumn('msg_date');
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
