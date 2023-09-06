<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWidgetCallByReferenceCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('widget_call_by_reference_cards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('reference_id')->unsigned()->index();
            $table->string('heading', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('redirect_url', 100)->nullable();
            $table->string('image')->nullable();
            $table->string('image_position')->comment('left or right')->nullable();
            $table->string('button_text')->nullable();
            $table->string('card_color')->nullable();
            $table->string('type')->comment('single-column or double-column')->nullable();
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
        Schema::dropIfExists('widget_call_by_reference_cards');
    }
}
