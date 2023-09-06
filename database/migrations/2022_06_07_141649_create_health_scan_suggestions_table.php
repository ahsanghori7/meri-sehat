<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHealthScanSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('health_scan_suggestions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            
            $table->unsignedBigInteger('doctor_id')->nullable();
            $table->foreign('doctor_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('article_id')->nullable();
            $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            
            $table->string('diagnosis', 100);
            $table->string('type', 50);
            $table->boolean('status')->default(1);
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
        Schema::dropIfExists('health_scan_suggestions');
    }
}
