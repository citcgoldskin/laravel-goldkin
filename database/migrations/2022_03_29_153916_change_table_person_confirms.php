<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeTablePersonConfirms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('person_confirms', function (Blueprint $table) {
            $table->dropColumn('pc_firstname');
            $table->dropColumn('pc_lastname');
            $table->dropColumn('pc_sei');
            $table->dropColumn('pc_mei');
            $table->dropColumn('pc_sex');
            $table->dropColumn('pc_birthday');

            $table->string('pc_reject_reason', 255)->nullable()->change();

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
