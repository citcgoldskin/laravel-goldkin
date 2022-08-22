<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUserFieldTypeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->change();
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();
            $table->string('user_firstname')->nullable()->change();
            $table->string('user_lastname')->nullable()->change();
            $table->string('user_sei')->nullable()->change();
            $table->string('user_mei')->nullable()->change();
            $table->unsignedSmallInteger('user_sex')->nullable()->change();
            $table->date('user_birthday')->nullable()->change();
            $table->integer('user_area_id')->nullable()->change();
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
