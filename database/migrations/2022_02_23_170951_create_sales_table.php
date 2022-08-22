<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->increments('sales_id');
            $table->unsignedInteger('sales_senpai_id');
            $table->unsignedInteger('sales_user_id');
            $table->unsignedInteger('sales_lesson_id');
            $table->unsignedInteger('sales_request_schedule_id');
            $table->unsignedInteger('sales_amount');
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
        Schema::dropIfExists('sales');
    }
}
