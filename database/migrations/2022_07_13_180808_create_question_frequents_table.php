<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionFrequentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('question_frequents', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('question_id')->nullable()->comment('質問ID');
            $table->tinyInteger('class')->default(0)->comment('カテゴリーID（0: すべて）');
            $table->integer('sort')->default(255)->comment('ソート');

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
        Schema::dropIfExists('question_frequent');
    }
}
