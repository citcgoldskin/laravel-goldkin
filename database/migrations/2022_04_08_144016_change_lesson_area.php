<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLessonArea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_areas', function (Blueprint $table) {
            $table->dropColumn('la_area_id');
            $table->integer('la_deep1_id')->after('la_lesson_id')->nullable();
            $table->integer('la_deep2_id')->after('la_deep1_id')->nullable();
            $table->integer('la_deep3_id')->after('la_deep2_id')->nullable();
            $table->integer('la_deep4_id')->after('la_deep3_id')->nullable();
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
