<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReasonForVisit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('reason_for_visit')){
            Schema::create('reason_for_visit', function (Blueprint $table) {
                $table->id();
                $table->string('reason');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql')->hasTable('reason_for_visit')){
            Schema::dropIfExists('reason_for_visit');
        }
    }
}
