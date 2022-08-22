<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAreaFieldsToAreas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->dropColumn('area_parent_id');
            $table->dropColumn('area_sort');
            $table->dropColumn('area_region');
        });

        Schema::table('areas', function (Blueprint $table) {
            $table->integer('area_pref')->nullable()->after('area_name')->comment('アリアID（都道府県）');
            $table->integer('area_region')->nullable()->after('area_name')->comment('アリアID');
            $table->string('area_code')->nullable()->after('area_name')->comment('アリアコード');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('areas', function (Blueprint $table) {
            //
        });
    }
}
