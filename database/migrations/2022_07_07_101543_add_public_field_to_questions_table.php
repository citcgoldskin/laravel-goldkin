<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicFieldToQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->tinyInteger('que_status')->default(1)->after('que_answer')->comment('ステータス 0: 下書きとして保存, 1: 保存');
            $table->dateTime('que_delete_at')->nullable()->after('que_answer')->comment('削除日時');
            $table->json('que_update_data')->nullable()->after('que_answer')->comment('変更予約情報');
            $table->dateTime('que_update_at')->nullable()->after('que_answer')->comment('変更日時');
            $table->dateTime('que_public_at')->nullable()->after('que_answer')->comment('公開日時');
            $table->tinyInteger('que_public')->default(0)->after('que_answer')->comment('0: 非公開, 1: 公開');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
}
