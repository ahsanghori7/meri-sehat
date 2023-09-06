<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeoImageInArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql3')->table('articles', function (Blueprint $table) {
            if (!Schema::connection('mysql3')->hasColumn('articles', 'seo_image')){
                $table->string('seo_image')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql3')->table('articles', function (Blueprint $table) {
            if (Schema::connection('mysql3')->hasColumn('articles', 'seo_image')){
                $table->dropColumn('seo_image');
            }
        });
    }
}
