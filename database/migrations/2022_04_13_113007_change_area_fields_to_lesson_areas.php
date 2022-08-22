<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAreaFieldsToLessonAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_areas', function (Blueprint $table) {
            $table->dropColumn('la_deep4_id');
        });

        Schema::table('lesson_areas', function (Blueprint $table) {
            $table->json('position')->nullable()->after('la_deep3_id')->comment('クリックされた位置');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_areas', function (Blueprint $table) {
            //
        });
    }
}
