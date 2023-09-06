<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UserNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('user_notification', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable();
            $table->string('ref_id')->nullable();
            $table->string('title');
            $table->string('sub_title');
            $table->string('type');
            $table->string('type_data');
            $table->string('text')->nullable();
            $table->string('module');
            $table->text('payload');
            $table->bigInteger('read_status')->default(0);
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
        //
        Schema::drop('user_notification');
    }
}
