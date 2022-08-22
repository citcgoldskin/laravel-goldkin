<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruits', function (Blueprint $table) {
            $table->increments('rc_id');
            $table->integer('rc_user_id');
            $table->integer('rc_class_id')->comment('カテゴリー');
            $table->string('rc_title', 50)->comment('募集タイトル');
            $table->date('rc_date')->comment('募集日時');
            $table->time('rc_start_time');
            $table->time('rc_end_time');
            $table->tinyInteger('rc_lesson_period_from')->comment('レッスン時間');
            $table->tinyInteger('rc_lesson_period_to')->comment('レッスン時間');
            $table->tinyInteger('rc_man_num')->comment('男性(参加人数)');
            $table->tinyInteger('rc_woman_num')->comment('女性(参加人数)');
            $table->integer('rc_wish_minmoney')->comment('希望金額');
            $table->integer('rc_wish_maxmoney')->comment('希望金額');
            $table->string('rc_place', 255)->comment('レッスン場所');
            $table->string('rc_place_detail', 200)->nullable()->comment('待ち合わせ場所の詳細');
            $table->string('rc_detail', 1000)->nullable()->comment('募集詳細');
            $table->tinyInteger('rc_req_sex')->comment('希望する性別( 0:指定なし, 1:女性, 2:男性)');
            $table->tinyInteger('rc_req_age_from')->comment('希望する年齢');
            $table->tinyInteger('rc_req_age_to')->comment('希望する年齢');
            $table->date('rc_period')->nullable()->comment('募集期限');
            $table->tinyInteger('rc_state')->comment('0: 下書, 1: 申請中, 2:募集中, 3:complete, 4: 過去投稿');
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
        Schema::dropIfExists('recruits');
    }
}
