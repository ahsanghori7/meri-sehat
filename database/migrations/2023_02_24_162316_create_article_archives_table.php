<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if (!Schema::connection('mysql3')->hasTable('article_archives'))
            Schema::connection('mysql3')->create('article_archives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('approved_by')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('written_by')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('parent_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('lang_id')->nullable()->unsigned()->index();
            $table->unsignedBigInteger('translation_od')->nullable()->unsigned()->index();
            $table->string('type',50)->default("test");
            $table->string('name',100)->default("test");
            $table->string('meta_description')->nullable()->default("test");
            $table->string('meta_name',255)->nullable()->default("test");
            $table->string('slug',100)->default("test");
            $table->string('description')->nullable()->default("test");
            $table->boolean('is_description_show')->nullable()->default(true);
            $table->string('keyword',100)->nullable()->default("test");
            $table->string('image',500)->nullable()->default("test");
            $table->boolean('status')->default(true);
            $table->string('class_name',255)->nullable()->default("test");
            $table->timestamps();
            $table->boolean('hide_image_in_detail')->default(true);
            $table->boolean('is_featured')->default(true);
            $table->unsignedBigInteger('visit_counts')->unsigned()->index()->default(0);
            $table->string('home_image',255)->nullable();
            $table->boolean('hide_home_image_in_detail')->default(true);
            $table->boolean('is_featured2')->default(true);
            $table->boolean('is_app_show')->default(true);
            $table->boolean('is_web_show')->default(true);
            $table->unsignedBigInteger('speciality_id')->nullable()->unsigned()->index();
            $table->string('alt',255)->nullable()->default("test");
            $table->string('canonical_link',255)->nullable()->default("test");
            $table->string('seo_image',255)->nullable()->default("test");
            $table->string('hidden_description',255)->nullable()->default("test");
            $table->boolean('draft')->default(true);
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::connection('mysql3')->table('article_archives', function (Blueprint $table) {

                Schema::dropIfExists('article_archives');
           
        });
    }
}
