<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropNoteStatusFieldsToAppealReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appeal_reports', function (Blueprint $table) {
            $table->dropColumn('note');
            $table->dropColumn('status');
            $table->dropColumn('reported_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appeal_reports', function (Blueprint $table) {
            //
        });
    }
}
