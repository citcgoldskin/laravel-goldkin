<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransferApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_applications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ta_user_id')->nullable()->comment('振込申請者ID');
            $table->integer('ta_bank_id')->nullable()->comment('銀行');
            $table->tinyInteger('ta_bank_account_type')->nullable()->comment('口座種別');
            $table->string('ta_bank_branch')->nullable()->comment('支店コード');
            $table->string('ta_bank_account_no')->nullable()->comment('口座番号');
            $table->string('ta_bank_account_name')->nullable()->comment('口座名義');
            $table->double('ta_send_total_price')->nullable()->comment('振込総額');
            $table->double('ta_fee')->nullable()->comment('振込手数料');
            $table->double('ta_profit_price')->nullable()->comment('振込金額');
            $table->tinyInteger('ta_transfer_status')->nullable()->comment('振込ステータス 1:振込申請, 2:振込完了');
            $table->dateTime('ta_schedule_date')->nullable()->comment('振込予定日');
            $table->dateTime('ta_application_date')->nullable()->comment('振込申請日');
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
        Schema::dropIfExists('transfer_applications');
    }
}
