<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class   CreateQuestionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_classes', function (Blueprint $table) {
            $table->increments('qc_id');
            $table->string('qc_name', 255);
            $table->integer('qc_parent');
            $table->unsignedTinyInteger('qc_sort')->default(255);
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
        Schema::dropIfExists('question_classes');
    }
}
