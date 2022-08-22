<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonChangeHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_change_history', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->integer('lesson_id')->nullable()->comment('レッスンID');
            $table->tinyInteger('op_type')->default(1)->comment('1: 新規作成, 2:変更');
            $table->json('changed_data')->nullable()->comment('変更データ');
            $table->json('origin_data')->nullable()->comment('データ');
            $table->string('updated_by')->nullable()->comment('更新者ID');
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
        Schema::dropIfExists('lesson_change_history');
    }
}
