<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDrugArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('drug_archives'))
        Schema::connection('mysql3')->create('drug_archives', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('translation_of')->nullable()->unsigned()->index();
            $table->bigInteger('lang_id')->unsigned()->index();
            $table->string('name', 100);
            $table->string('slug', 100);
            $table->string('description', 100);
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->bigInteger('drug_id')->unsigned()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('drug_archives', function (Blueprint $table) {
        Schema::dropIfExists('drug_archives');
        });
    }
}
