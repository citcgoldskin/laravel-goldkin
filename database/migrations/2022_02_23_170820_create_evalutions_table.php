<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvalutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evalutions', function (Blueprint $table) {
            $table->increments('eval_id');
            $table->unsignedInteger('eval_user_id');
            $table->tinyInteger('eval_kind')->comment('0: senpai \'s eval, 1: kouhai \'s eval');
            $table->unsignedInteger('eval_lesson_id');
            $table->tinyInteger('eval_type_id');
            $table->tinyInteger('eval_val')->comment('0:いいえ , 1: はい');
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
        Schema::dropIfExists('evalutions');
    }
}
