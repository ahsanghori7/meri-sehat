<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('duration', 50);
            $table->string('duration_text', 100);
            $table->string('color_code', 50);
            $table->double('price');
            $table->double('discounted_price')->nullable();
            $table->text('description')->nullable();
            $table->text('addon_heading')->nullable();
            $table->text('addon_text')->nullable();
            $table->boolean('is_starred')->default(false);
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
        Schema::dropIfExists('subscriptions');
    }
}
