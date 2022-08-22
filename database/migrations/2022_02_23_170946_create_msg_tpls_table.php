<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMsgTplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('msg_tpls', function (Blueprint $table) {
            $table->string('mt_code', 50);
            $table->string('mt_name', 50);
            $table->string('mt_msg_content', 255);
            $table->string('mt_sms_content', 255);
            $table->string('mt_mail_subject', 255);
            $table->text('mt_mail_content');
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
        Schema::dropIfExists('msg_tpls');
    }
}
