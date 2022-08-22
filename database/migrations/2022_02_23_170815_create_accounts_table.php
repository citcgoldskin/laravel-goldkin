<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->increments('act_id')->comment('ID');
            $table->unsignedInteger('act_user_id');
            $table->integer('act_bank_id');
            $table->integer('act_type');
            $table->string('act_suboffice_code', 50);
            $table->string('act_number',50);
            $table->string('act_name',50)->comment('口座名義（カナ）');
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
        Schema::dropIfExists('accounts');
    }
}
