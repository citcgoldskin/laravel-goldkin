<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonDiscussAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_discuss_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lda_lesson_id')->nullable();
            $table->integer('lda_deep1_id')->nullable();
            $table->integer('lda_deep2_id')->nullable();
            $table->integer('lda_deep3_id')->nullable();
            $table->json('position')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lesson_discuss_areas');
    }
}
