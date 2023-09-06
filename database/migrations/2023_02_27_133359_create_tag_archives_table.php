<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('tag_archives'))
            Schema::connection('mysql3')->create('tag_archives', function (Blueprint $table){
                $table->id();
                $table->string('name')->unique();
                $table->boolean('restricted')->default(false);
                $table->boolean('status')->default(1);
                $table->timestamps();
                $table->bigInteger('tags_id')->unsigned()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('tag_archives', function (Blueprint $table) {
        Schema::dropIfExists('tag_archives');
        });
    }
}
