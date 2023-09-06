<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('lang_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->unsigned()->index();
            $table->string('reference_type', 100)->nullable();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->text('descripton')->nullable();
            $table->string('keywords', 100)->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
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
        Schema::dropIfExists('pages');
    }
}
