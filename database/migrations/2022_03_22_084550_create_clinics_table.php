<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClinicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clinics', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('address', 50)->nullable();
            $table->string('lat', 50)->nullable();
            $table->string('long', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('phone_secondary', 50)->nullable();
            $table->string('email', 50)->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clinics');
    }
}
