<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_mail', 100)->after('user_birthday')->comment('郵便番号');
            $table->string('user_county', 100)->after('user_area_id')->comment('市区町村');
            $table->string('user_village', 100)->after('user_county')->comment('町番地');
            $table->string('user_mansyonn', 100)->after('user_village')->comment('マンション名・部屋番号');
            $table->tinyInteger('user_is_senpai')->after('remember_token')->comment('1:senpai, 0:no')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
