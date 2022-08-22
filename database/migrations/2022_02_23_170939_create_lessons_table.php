<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->increments('lesson_id');
            $table->unsignedInteger('lesson_senpai_id');
            $table->tinyInteger('lesson_type')->comment('0:対面レッスン型, 1:オンライン型');
            $table->integer('lesson_class_id')->comment('カテゴリー');
            $table->string('lesson_title', 50)->comment('レッスンタイトル');
            $table->string('lesson_image',1000)->comment('array string, レッスンイメージ');
            $table->tinyInteger('lesson_wish_sex')->comment('0:指定なし, 1:女性,2:男性');
            $table->tinyInteger('lesson_wish_minage')->comment('希望する年齢');
            $table->tinyInteger('lesson_wish_maxage')->comment('希望する年齢');
            $table->tinyInteger('lesson_min_hours')->comment('最低レッスン時間');
            $table->unsignedInteger('lesson_30min_fees')->comment('30分あたりのレッスン料金');
            $table->tinyInteger('lesson_person_num')->comment('対応人数');
            $table->tinyInteger('lesson_able_with_man')->comment('1: 女性同伴で男性受付可');
            $table->tinyInteger('lesson_accept_without_map')->comment('1: レッスン場所を指定せずに相談を受付ける');
            $table->string('lesson_map_pos',50)->comment('地図');
            $table->string('lesson_address_and_keyword', 50)->comment('住所やキーワード');
            $table->string('lesson_pos_detail', 100)->comment('待ち合わせ場所の詳細');
            $table->tinyInteger('lesson_able_discuss_pos')->comment('1: レッスン場所の相談可');
            $table->string('lesson_service_details',1000)->comment('サービス詳細');
            $table->string('lesson_other_details',200)->comment('持ち物・その他の費用（200文字まで）');
            $table->string('lesson_buy_and_attentions',200)->comment('購入にあたってのお願い・注意事項');
            $table->tinyInteger('lesson_accept_attend_request')->comment('1: 出勤リクエストを受付する');
            $table->string('lesson_condition', 100)->comment('array string, 手ぶらOK, 顔写真あり ...');
            $table->tinyInteger('lesson_state')->comment('0: 下書き, 1: 非公開,  2: 申請中, 3:出品中, 4:非承認');
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
        Schema::dropIfExists('lessons');
    }
}
