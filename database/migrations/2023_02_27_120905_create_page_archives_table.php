<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePageArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql3')->hasTable('page_archives'))
        Schema::connection('mysql3')->create('page_archives', function (Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('lang_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('reference_id')->nullable()->unsigned()->index();
            $table->string('reference_type', 100)->nullable();
            $table->unsignedBigInteger('reviewed_by')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('written_by')->nullable()->unsigned()->index();
            $table->string('name', 100);
            $table->text('meta_description');
            $table->string('meta_name', 255);
            $table->string('slug', 100);
            $table->text('descripton')->nullable();
            $table->boolean('is_description_show')->default(true);
            $table->string('keywords', 100)->nullable();
            $table->string('image',255)->nullable();
            $table->boolean('status')->default(true);
            $table->string('class_name',255)->nullable();
            $table->boolean('is_web_show')->default(true);
            $table->boolean('is_app_show')->default(true);
            $table->softDeletes();
            $table->timestamps();
            $table->boolean('hide_image_in_detail')->default(true);
            $table->unsignedBigInteger('visit_counts')->unsigned()->index();
            $table->string('home_image',255)->nullable();
            $table->boolean('hide_home_image_in_detail')->default(true);
            $table->unsignedBigInteger('speciality_id')->nullable()->unsigned()->index();
            $table->boolean('is_featured')->default(true);
            $table->string('alt',255)->nullable();
            $table->string('canonical_link',255)->nullable();
            $table->string('seo_image',255)->nullable();
            $table->string('hidden_description',255)->nullable();
            $table->bigInteger('page_id')->unsigned()->nullable();

           
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('page_archives', function (Blueprint $table) {
            Schema::dropIfExists('page_archives');
        });
    }
}
