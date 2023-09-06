<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveUniqueConstraintFromUserSocialAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_social_accounts', function (Blueprint $table) {
            $table->dropUnique('user_social_accounts_social_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_social_accounts', function (Blueprint $table) {
            $table->unique('social_id');
        });
    }
}
