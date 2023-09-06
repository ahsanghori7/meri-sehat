<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpertFollow extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expert_follows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('expert_id');
            $table->foreign('expert_id')->references('id')->on('experts')->onDelete('cascade');
            $table->text('linkedin')->nullable();
            $table->text('twitter')->nullable();
            $table->text('skype')->nullable(); 
            $table->string('link')->nullable();
            $table->boolean('status')->default(1);
            $table->softDeletes();
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
        Schema::dropIfExists('expert_follows');
    }
}
