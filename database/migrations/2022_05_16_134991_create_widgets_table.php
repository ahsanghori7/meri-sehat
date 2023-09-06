<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('key');
            $table->string('web_key');
            $table->string('mobile_key');
            $table->string('mobile_image');
            $table->string('web_image');
            $table->boolean('type')->default(1)->comment('1 => Editable, 2 => Predefined');
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
        Schema::dropIfExists('widgets');
    }
}
