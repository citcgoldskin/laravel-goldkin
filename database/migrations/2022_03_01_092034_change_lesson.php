<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLesson extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->tinyInteger('lesson_cond_1')->after('lesson_accept_attend_request')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_2')->after('lesson_cond_1')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_3')->after('lesson_cond_2')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_4')->after('lesson_cond_3')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_5')->after('lesson_cond_4')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_6')->after('lesson_cond_5')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_7')->after('lesson_cond_6')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_8')->after('lesson_cond_7')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_9')->after('lesson_cond_8')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_10')->after('lesson_cond_9')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_11')->after('lesson_cond_10')->comment('0: No, 1: Yes');
            $table->tinyInteger('lesson_cond_12')->after('lesson_cond_11')->comment('0: No, 1: Yes');
            $table->dropColumn('lesson_condition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lessons');
    }
}
