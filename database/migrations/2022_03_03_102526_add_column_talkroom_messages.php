<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnTalkroomMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('talkroom_messages', function (Blueprint $table) {
            $table->unsignedInteger('lesson_request_id')->comment('lesson request id')->after('talkroom_id');
            $table->dropColumn('show_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('talkroom_messages', function (Blueprint $table) {
            $table->dropColumn('lesson_request_id');
        });
    }
}
