<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferenceWidgetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reference_widgets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('widget_id');
            $table->foreign('widget_id')->references('id')->on('widgets')->onDelete('cascade');
            $table->unsignedBigInteger('reference_id')->unsigned()->index();
            $table->string('reference_type')->comment('article, page');

            $table->string('heading')->nullable();
            $table->text('description')->nullable();
            $table->string('redirect_url')->nullable();
            $table->string('json_param')->nullable();
            $table->integer('sequence')->default(0);
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
        Schema::dropIfExists('reference_widgets');
    }
}
