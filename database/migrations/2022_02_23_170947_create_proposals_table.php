<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->increments('pro_id');
            $table->unsignedInteger('pro_user_id');
            $table->unsignedInteger('pro_rc_id');
            $table->integer('pro_money')->comment('提案金額');
            $table->time('pro_start_time')->comment('提案日時');
            $table->time('pro_end_time')->comment('提案日時');
            $table->string('pro_msg', 500)->nullable()->comment('メッセージ');
            $table->dateTime('pro_buy_datetime')->comment('購入期限');
            $table->tinyInteger('pro_state')->comment('0:  draft,  1: request, 2: complete');
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
        Schema::dropIfExists('proposals');
    }
}
