<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_requests', function (Blueprint $table) {
            $table->increments('lr_id');
            $table->unsignedInteger('lr_lesson_id');
            $table->unsignedInteger('lr_user_id');
            $table->tinyInteger('lr_type')->comment('0: reserve, 1:attendance');
            $table->tinyInteger('lr_hope_type')->comment('0: 対面希望, 1:オンライン希望');
            $table->tinyInteger('lr_man_num')->comment('男性');
            $table->tinyInteger('lr_woman_num')->comment('女性');
            $table->integer('ar_hope_mintime')->comment('希望レッスン時間(attend only)');
            $table->integer('ar_hope_maxtime')->comment('希望レッスン時間(attend only)');
            $table->tinyInteger('lr_target_reserve')->comment('指定地で予約する');
            $table->tinyInteger('lr_pos_discuss')->comment('レッスン場所を相談する');
            $table->tinyInteger('ar_area_id')->comment('エリアを選択(attend only)');
            $table->string('ar_address', 50)->comment('続きの住所を入力');
            $table->string('ar_address_detail',200)->comment('待ち合わせ場所の詳細');
            $table->date('lr_until_confirm')->comment('リクエストの承認期限');
            $table->tinyInteger('lr_state')->comment('0:  request,   1: reserve, 2: complete, 3: cancel');
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
        Schema::dropIfExists('lesson_requests');
    }
}
