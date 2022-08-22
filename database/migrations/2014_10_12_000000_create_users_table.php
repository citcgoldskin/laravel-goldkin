<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('user_id');
            $table->string('name')->comment('nickname');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('user_firstname',50);
            $table->string('user_lastname', 50);
            $table->string('user_sei', 50);
            $table->string('user_mei', 50);
            $table->tinyInteger('user_sex');
            $table->date('user_birthday');
            $table->integer('user_area_id');
            $table->string('user_avatar', 50)->nullable();
            $table->string('user_phone', 11)->nullable();
            $table->string('user_intro', 1000)->nullable();
            $table->tinyInteger('user_state')->default(1);

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
        Schema::dropIfExists('users');
    }
}
