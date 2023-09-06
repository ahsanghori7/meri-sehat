<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateActivityLogTable extends Migration
{
    public function up()
    {
        Schema::connection('mysql2')->create('log_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('subject');
            $table->longText('old_value')->nullable();
            $table->longText('new_value')->nullable();
            $table->string('event');
            $table->string('table');
            $table->string('module');
            $table->text('url');
            $table->string('method');
            $table->string('ip');
            $table->string('agent')->nullable();
            $table->integer('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('log_activities');
    }
}
