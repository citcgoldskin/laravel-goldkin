<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainVisualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_visuals', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->string('type')->nullable()->comment('メインビジュアルタイプ');
            $table->string('file_path')->nullable()->comment('画像ファイル名');
            $table->string('link')->nullable()->comment('画像クリック後の参照先');
            $table->text('description')->nullable()->comment('説明');
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
        Schema::dropIfExists('main_visuals');
    }
}
