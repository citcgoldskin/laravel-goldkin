<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPublicFieldToQuestionClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('question_classes', function (Blueprint $table) {
            $table->tinyInteger('qc_public')->default(0)->after('qc_parent')->comment('0: 非公開, 1: 公開');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('question_classes', function (Blueprint $table) {
            //
        });
    }
}
