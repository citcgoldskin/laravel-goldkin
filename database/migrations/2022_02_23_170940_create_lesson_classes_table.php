<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_classes', function (Blueprint $table) {
            $table->increments('class_id');
            $table->string('class_name',50);
            $table->string('class_image', 50)->nullable();
            $table->string('class_icon', 50);
            $table->unsignedTinyInteger('class_sort')->default(255);
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
        Schema::dropIfExists('lesson_classes');
    }
}
