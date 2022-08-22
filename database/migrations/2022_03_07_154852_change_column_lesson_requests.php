<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnLessonRequests extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lesson_requests', function (Blueprint $table){
            $table->dateTime('lr_request_date')->nullable()->after('lr_state');
            $table->dateTime('lr_reverse_date')->nullable()->after('lr_request_date');
            $table->dateTime('lr_complete_date')->nullable()->after('lr_reverse_date');
            $table->dateTime('lr_cancel_date')->nullable()->after('lr_complete_date');
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
