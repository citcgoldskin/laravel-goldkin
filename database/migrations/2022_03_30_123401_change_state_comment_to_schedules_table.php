<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeStateCommentToSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_request_schedules', function (Blueprint $table) {
            $table->integer('lrs_state')->comment('0:request,1:confirm,2:reserve,3:start,4:complete,5:modified,6:cnacel_senpai,7:cancel_kouhai,8:cancel_system')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('schedules', function (Blueprint $table) {
            //
        });
    }
}
