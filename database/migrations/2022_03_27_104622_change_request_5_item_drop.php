<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeRequest5ItemDrop extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_requests', function (Blueprint $table) {
            $table->dropColumn('lr_man_num');
            $table->dropColumn('lr_woman_num');
            $table->dropColumn('lr_area_id');
            $table->dropColumn('lr_address');
            $table->dropColumn('lr_address_detail');
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
