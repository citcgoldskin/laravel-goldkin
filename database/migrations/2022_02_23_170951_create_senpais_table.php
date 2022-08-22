<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSenpaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('senpais', function (Blueprint $table) {
            $table->increments('senpai_id')->comment('センパイ');
            $table->unsignedInteger('senpai_user_id');
            $table->string('senpai_mail', 50)->comment('郵便番号');
            $table->integer('senpai_area_id')->comment('都道府県');
            $table->string('senpai_county', 50)->comment('市区町村');
            $table->string('senpai_village', 50)->comment('町番地');
            $table->string('senpai_mansyonn', 50)->comment('マンション名・部屋番号');
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
        Schema::dropIfExists('senpais');
    }
}
