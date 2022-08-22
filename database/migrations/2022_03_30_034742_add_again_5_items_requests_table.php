<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgain5ItemsRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_requests', function (Blueprint $table) {
            $table->tinyInteger('lr_man_num')->nullable()->after('lr_type');
            $table->tinyInteger('lr_woman_num')->nullable()->after('lr_man_num');
            $table->tinyInteger('lr_area_id')->nullable()->after('lr_woman_num');
            $table->string('lr_address')->nullable()->after('lr_area_id');
            $table->string('lr_address_detail')->nullable()->after('lr_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_requests', function (Blueprint $table) {
            $table->dropColumn('lr_man_num');
            $table->dropColumn('lr_woman_num');
            $table->dropColumn('lr_area_id');
            $table->dropColumn('lr_address');
            $table->dropColumn('lr_address_detail');
        });
    }
}
