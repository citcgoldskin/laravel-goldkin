<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCardCreditsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_credits', function (Blueprint $table) {
            $table->increments('cc_id');
            $table->unsignedInteger('cc_user_id');
            $table->unsignedInteger('cc_company_id')->comment('カード会社 ID');
            $table->string('cc_number')->comment('カード番号');
            $table->date('cc_validation_date')->comment('有効期限');
            $table->string('cc_client_name')->comment('名義人');
            $table->string('cc_security_code')->comment('セキュリティーコード');
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
        Schema::dropIfExists('card_credits');
    }
}
