<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePunishmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('punishments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->comment('ぴろしきまるユーザーのID');
            $table->integer('stop_period')->nullable()->comment('停止期間 7, 30, 60, 90');
            $table->tinyInteger('type')->nullable()->comment("決定の種類");
            $table->json('basis')->nullable()->comment("根拠");
            $table->json('reason')->nullable()->comment("理由");
            $table->string('alert_title', 256)->nullable()->comment("表題");
            $table->text('alert_text')->nullable()->comment("通知文");
            $table->dateTime('decided_at')->nullable()->comment("決定日時");
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
        Schema::dropIfExists('punishments');
    }
}
