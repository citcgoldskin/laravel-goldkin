<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->increments('id')->comment('ID');
            $table->tinyInteger('category')->nullable()->comment('カテゴリー');
            $table->text('title')->nullable()->comment('表題');
            $table->text('content')->nullable()->comment('本文');
            $table->tinyInteger('status')->default(0)->comment('ステータス');
            $table->dateTime('publish_time')->nullable()->comment('公開日時');
            $table->dateTime('dis_publish_time')->nullable()->comment('非公開日時');
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
        Schema::dropIfExists('news');
    }
}
