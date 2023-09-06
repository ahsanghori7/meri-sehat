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
        if (!Schema::hasTable('fitness_experts_experiences'))
        Schema::create('fitness_experts_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fitness_experts_id');
            $table->foreign('fitness_experts_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('position', 50);
            $table->string('institute', 50);
            $table->string('start_year',50)->nullable();
            $table->integer('end_year')->nullable();
            $table->integer('is_completed')->default('1');
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
        Schema::dropIfExists('fitness_experts_experiences');
    }
};
