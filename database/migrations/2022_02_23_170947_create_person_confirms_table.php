<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonConfirmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('person_confirms', function (Blueprint $table) {
            $table->increments('pc_id');
            $table->unsignedInteger('pc_user_id');
            $table->string('pc_firstname', 50);
            $table->string('pc_lastname', 50);
            $table->string('pc_sei', 50);
            $table->string('pc_mei', 50);
            $table->tinyInteger('pc_sex');
            $table->date('pc_birthday');
            $table->string('pc_confirm_doc', 255)->comment('本人確認に利用できる書類');
            $table->tinyInteger('pc_state')->comment('0:  making, 1: review, 2: confirmed, 3: rejected');
            $table->string('pc_reject_reason', 50);
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
        Schema::dropIfExists('person_confirms');
    }
}
