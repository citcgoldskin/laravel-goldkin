<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppealReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appeal_reports', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('appeal_id')->nullable()->comment('通報ID');
            $table->tinyInteger('type')->nullable()->comment("通報理由の種類");
            $table->string('note', 256)->nullable()->comment('通報詳細');
            $table->tinyInteger('status')->default(0)->comment('0: unread, 1: read');
            $table->dateTime('reported_at')->nullable()->comment('通報日付');
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
        Schema::dropIfExists('appeal_reports');
    }
}
