<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDiseaseArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('disease_archives'))
            Schema::connection('mysql3')->create('disease_archives', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('translation_of')->nullable()->unsigned()->index();
                $table->bigInteger('lang_id')->unsigned()->index();
                $table->string('name', 100);
                $table->string('slug', 100);
                $table->string('description', 500);
                $table->boolean('status')->default(true);
                $table->timestamps();
                $table->bigInteger('visit_counts')->unsigned()->nullable();
                $table->bigInteger('approved_by')->unsigned()->nullable();
                $table->bigInteger('written_by')->unsigned()->nullable();
                $table->bigInteger('speciality_id')->unsigned()->nullable();
                $table->bigInteger('disease_id')->unsigned()->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('disease_archives', function (Blueprint $table) {
        Schema::dropIfExists('disease_archives');
        });
    }
}
