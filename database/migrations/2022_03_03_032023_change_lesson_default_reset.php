<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeLessonDefaultReset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('lesson_address_and_keyword', 50)->nullable()->change();
            $table->string('lesson_pos_detail', 100)->nullable()->change();
            $table->string('lesson_service_details',1000)->nullable()->change();
            $table->string('lesson_other_details',200)->nullable()->change();
            $table->string('lesson_buy_and_attentions',200)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
