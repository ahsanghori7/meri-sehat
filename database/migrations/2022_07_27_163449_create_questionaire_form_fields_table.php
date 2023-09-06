<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionaireFormFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questionaire_form_fields', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('questionaire_form_id')->unsigned()->index();
            $table->foreign('questionaire_form_id')->references('id')->on('questionaire_form')->onDelete('cascade');
            $table->string('key')->nullable();
            $table->string('input_type')->nullable();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->text('json_params')->nullable();
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
        Schema::dropIfExists('questionaire_form_fields');
    }
}
