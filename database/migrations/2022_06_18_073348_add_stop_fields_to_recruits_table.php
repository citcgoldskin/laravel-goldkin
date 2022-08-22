<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStopFieldsToRecruitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('recruits', function (Blueprint $table) {
            $table->dateTime('rc_stop_cancel_reverse_at')->nullable()->after('rc_state')->comment('公開停止の取り消し予約日付');
            $table->dateTime('rc_stopped_at')->nullable()->after('rc_state')->comment('公開停止日付');
            $table->tinyInteger('rc_stop')->default(0)->after('rc_state')->comment('投稿停止ステータス 1:公開停止');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('recruits', function (Blueprint $table) {
            //
        });
    }
}
