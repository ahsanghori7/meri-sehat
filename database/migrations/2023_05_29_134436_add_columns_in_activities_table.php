<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::connection('mysql')->hasTable('activities')){
            Schema::table('activities', function (Blueprint $table) {
                $table->longText('device_information')->nullable();
                $table->boolean('verified_login')->default(0);
                $table->string('device_type')->default('web');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activities', function (Blueprint $table) {
            $table->dropColumn('device_information');
            $table->dropColumn('device_type');
            $table->dropColumn('verified_login');
        });
    }
};
