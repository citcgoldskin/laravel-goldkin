<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBankFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('bank_account_name', 256)->nullable()->after('user_intro')->comment('口座名義(カナ)');
            $table->string('bank_account_no', 256)->nullable()->after('user_intro')->comment('口座番号');
            $table->integer('bank_account_type')->nullable()->after('user_intro')->comment('口座種別');
            $table->integer('bank_branch')->nullable()->after('user_intro')->comment('支店名');
            $table->integer('bank')->nullable()->after('user_intro')->comment('銀行名');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
