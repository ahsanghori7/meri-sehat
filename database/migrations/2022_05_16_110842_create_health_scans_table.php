<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthScansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('family_member_id')->nullable();
            $table->foreign('family_member_id')->references('id')->on('family_members')->onDelete('cascade');
            
            $table->string('blood_pressure', 50);
            $table->string('stress_level', 50);
            $table->string('respiratory_rate', 50);
            $table->string('spo2', 50);
            $table->string('heat_rate', 50);
            $table->string('bmi', 50);
            $table->text('diagnosis')->nullable();
            $table->text('recommendation')->nullable();
            $table->string('ip_address', 50)->nullable();
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
        Schema::dropIfExists('health_scans');
    }
}
