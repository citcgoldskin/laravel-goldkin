<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnRecruitsLatLong extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('recruits', function (Blueprint $table) {
            $table->double('rc_longitude')->nullable()->after('rc_place')->comment('経度');
            $table->double('rc_latitude')->nullable()->after('rc_longitude')->comment('緯度');
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
