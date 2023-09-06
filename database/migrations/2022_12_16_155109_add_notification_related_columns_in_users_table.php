<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificationRelatedColumnsInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_welcome_message')->default(0);
            $table->boolean('is_sehat_scan_message')->default(0);
            $table->boolean('is_welcome_email')->default(0);
            $table->boolean('is_welcome_push')->default(0);
            $table->boolean('is_sehat_scan_push')->default(0);
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
            $table->dropColumn('is_welcome_message');
            $table->dropColumn('is_sehat_scan_message');
            $table->dropColumn('is_welcome_email');
            $table->dropColumn('is_welcome_push');
            $table->dropColumn('is_sehat_scan_push');
        });
    }
}
