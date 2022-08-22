<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaintenanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('maintenance', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->dateTime('start_time')->nullable()->comment('開始日時');
            $table->dateTime('end_time')->nullable()->comment('終了日時');
            $table->text('services')->nullable()->comment('対象サービス');
            $table->dateTime('notice_time')->nullable()->comment('通知日時');
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
        Schema::dropIfExists('maintenance');
    }
}
