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
        if (!Schema::connection('mysql')->hasTable('mobile_devices')){
            Schema::create('mobile_devices', function (Blueprint $table) {
                $table->id();
                $table->string('device_name');
                $table->integer('score');
                $table->timestamps();
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
        if (Schema::connection('mysql')->hasTable('mobile_devices')){
            Schema::dropIfExists('mobile_devices');
        }
    }
};
