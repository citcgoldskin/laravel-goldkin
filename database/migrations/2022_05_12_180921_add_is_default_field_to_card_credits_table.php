<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDefaultFieldToCardCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('card_credits', function (Blueprint $table) {
            $table->tinyInteger('cc_is_default')->default(0)->after('cc_user_id')->comment('既定カード');
            $table->string('cc_square_card_id')->nullable()->after('cc_user_id')->comment('SquareカードID');
            $table->string('cc_card_brand')->nullable()->after('cc_company_id')->comment('カードブランド名');
            $table->json('cc_data')->nullable()->after('cc_security_code')->comment('Square戻り値');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('card_credits', function (Blueprint $table) {
            $table->dropColumn('cc_is_default');
            $table->dropColumn('cc_square_card_id');
            $table->dropColumn('cc_card_brand');
            $table->dropColumn('cc_data');
        });
    }

}
