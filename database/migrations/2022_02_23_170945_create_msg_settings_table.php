<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsgSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_settings', function (Blueprint $table) {
            $table->string('ms_mt_code', 50);
            $table->tinyInteger('ms_push')->comment('1: on, 0:off');
            $table->tinyInteger('ms_email')->comment('1: on, 0:off');
            $table->unsignedInteger('ms_user_id');
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
        Schema::dropIfExists('msg_settings');
    }
}
