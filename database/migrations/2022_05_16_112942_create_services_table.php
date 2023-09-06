<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('translation_of')->nullable()->unsigned()->index();
            $table->bigInteger('lang_id')->unsigned()->index();
            $table->unsignedBigInteger('speciality_id');
            $table->foreign('speciality_id')->references('id')->on('specialities')->onDelete('cascade');
            $table->string('name');
            $table->unsignedBigInteger('action_by');
            $table->foreign('action_by')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('services');
    }
}
