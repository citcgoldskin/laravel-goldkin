<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTalkroomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('talkrooms');
        Schema::create('talkrooms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->tinyInteger('menu_type')->comment('0: kouhai\'s menu, 1: senpai\'s menu');
            $table->unsignedInteger('talk_user_id');
            $table->unsignedInteger('state')->comment('0: talking, 1: finished');
            $table->timestamps();
            $table->softDeletes();
        });
        Schema::dropIfExists('talkroom_messages');
        Schema::create('talkroom_messages', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('talkroom_id');
            $table->tinyInteger('show_type')->comment('0: left, 1: center, 2: right');
            $table->tinyInteger('msg_type');
            $table->text('message');
            $table->tinyInteger('state')->comment('0: unread, 1: read');
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
        Schema::dropIfExists('talkrooms');
        Schema::dropIfExists('talkroom_messages');
    }
}
