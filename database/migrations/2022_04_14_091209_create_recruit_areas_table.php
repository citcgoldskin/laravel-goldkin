<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitAreasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruit_areas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ra_recruit_id');
            $table->integer('ra_deep1_id')->nullable();
            $table->integer('ra_deep2_id')->nullable();
            $table->integer('ra_deep3_id')->nullable();
            $table->json('position')->nullable()->comment('クリックされた位置');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recruit_areas');
    }
}
