<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeBank extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('banks', function (Blueprint $table)  {
            $table->dropColumn('bnk_level');
            $table->dropColumn('bnk_prefix_id');
            $table->string('bnk_prefix', 1)->after('bnk_name')->comment('50音順');
            $table->tinyInteger('bnk_fav')->after('bnk_prefix')->default(0);
            $table->tinyInteger('bnk_sort')->after('bnk_fav');

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
