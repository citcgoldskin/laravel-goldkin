<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLessonTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('lesson_map_pos');
            $table->dropColumn('lesson_address_and_keyword');
            $table->double('lesson_latitude', 100)->after('lesson_accept_without_map')->nullable()->comment('緯度');
            $table->double('lesson_longitude', 100)->after('lesson_latitude')->nullable()->comment('硬度');
            $table->string('lesson_area_ids', 255)->after('lesson_longitude')->comment('地域');
            $table->string('lesson_map_address', 255)->after('lesson_area_ids')->comment('講義場所の住所');
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
