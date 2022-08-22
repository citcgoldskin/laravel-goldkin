<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLessonStopFieldToLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dateTime('lesson_stop_cancel_reverse_at')->nullable()->after('lesson_state')->comment('公開停止の取り消し予約日付');
            $table->dateTime('lesson_stopped_at')->nullable()->after('lesson_state')->comment('公開停止日付');
            $table->tinyInteger('lesson_stop')->default(0)->after('lesson_state')->comment('レッスン停止ステータス 1:公開停止');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function (Blueprint $table) {
            //
        });
    }
}
