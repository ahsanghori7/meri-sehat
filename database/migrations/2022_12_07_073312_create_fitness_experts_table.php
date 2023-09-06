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
        Schema::create('fitness_experts_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fitness_experts_id');
            $table->foreign('fitness_experts_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('prefix', 10)->nullable();
            $table->string('experience_year', 10)->nullable();
            $table->text('about')->nullable();
            $table->string('visiting_card_image', 255)->nullable();
            $table->string('social_facebook', 255)->nullable();
            $table->string('social_twitter', 255)->nullable();
            $table->string('social_youtube', 255)->nullable();
            $table->string('social_instagram', 255)->nullable();
            $table->string('social_linkedin', 255)->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('fitness_experts');
    }
};
