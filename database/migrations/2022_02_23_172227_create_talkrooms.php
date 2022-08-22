<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTalkrooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('talkrooms', function (Blueprint $table) {
            $table->increments('talkroom_id');
            $table->unsignedInteger('talkroom_user_id');
            $table->unsignedInteger('talkroom_event_time');
            $table->tinyInteger('talkroom_type')->comment('0: senpai=> reseve request,1: senpai=> reseve change,2: senpai=> reseve confirm,3: senpai=> lesson canceled,4: kouhai=> reseve request,5: kouhai=> resrve change,6: kouhai=> resrve confirm,7: kouhai=> lesson canceled,8: system=> system info,9: senpai button => lesson buyied,10: senpai button => start!,11: senpai button => pos share cancel,12: senpai button => evaluation,13: kouhai button => lesson buyied,14: kouhai button => evaluation,15: senpai=> map,16: kouhai=> map,17: senpai=>senpai msg,18: senpai=>kouhai msg,19: kouhai=>senpai msg,20: kouhai=>kouhai msg');
            $table->string('talkroom_param', 255);
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
    }
}
