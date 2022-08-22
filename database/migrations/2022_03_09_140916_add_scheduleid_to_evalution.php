<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScheduleidToEvalution extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('evalutions', function (Blueprint $table) {
            //
            $table->unsignedInteger('eval_schedule_id')->after('eval_lesson_request_id')->comment('lesson request schedule id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('evalutions', function (Blueprint $table) {
            //
        });
    }
}
