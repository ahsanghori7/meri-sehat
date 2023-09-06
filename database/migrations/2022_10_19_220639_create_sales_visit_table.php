<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesVisitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sales_id');
            $table->unsignedBigInteger('doctor_id');
            $table->unsignedBigInteger('clinic_id')->nullable();
            $table->unsignedBigInteger('visit_type')->nullable();
            $table->unsignedBigInteger('feedback_type')->nullable();
            $table->string('comments', 255)->nullable();
            $table->string('lat', 50)->nullable();
            $table->string('long', 50)->nullable();
            $table->enum('status',['approved','unapproved'])->default('unapproved');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_visits');
    }
}
