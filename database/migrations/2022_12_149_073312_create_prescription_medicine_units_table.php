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
        if (!Schema::hasTable('prescription_medicine_units'))
        Schema::create('prescription_medicine_units', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->unsignedBigInteger('prescription_medicine_types_id');
//            $table->foreign('prescription_medicine_types_id')->references('id')->on('prescription_medicine_types')->onDelete('cascade');
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
        Schema::dropIfExists('prescription_medicine_types_id');
    }
};
