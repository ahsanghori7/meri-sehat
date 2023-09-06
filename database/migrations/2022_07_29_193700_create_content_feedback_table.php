<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('content_feedback', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('content_id');
            $table->foreign('content_id')->references('id')->on('pages')->onDelete('cascade');
            $table->string('feedback');
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
        Schema::dropIfExists('content_feedback');
    }
}
