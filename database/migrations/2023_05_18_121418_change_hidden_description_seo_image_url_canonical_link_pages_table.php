<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql3')->table('pages', function (Blueprint $table) {
            if (Schema::connection('mysql3')->hasColumn('pages', 'hidden_description')) //check the column
            {
                $table->mediumText('hidden_description')->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'seo_image')) //check the column
            {
                $table->mediumText('seo_image')->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_link')) //check the column
            {
                $table->mediumText('canonical_link')->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_title')) //check the column
            {
                $table->mediumText('canonical_title')->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_description')) //check the column
            {
                $table->mediumText('canonical_description')->nullable()->change();
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
        Schema::connection('mysql3')->table('pages', function (Blueprint $table) {
            if (Schema::connection('mysql3')->hasColumn('pages', 'hidden_description')) //check the column
            {
                $table->string('hidden_description', 255)->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'seo_image')) //check the column
            {
                $table->string('seo_image', 255)->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_link')) //check the column
            {
                $table->string('canonical_link', 255)->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_title')) //check the column
            {
                $table->string('canonical_title', 255)->nullable()->change();
            }
            if (Schema::connection('mysql3')->hasColumn('pages', 'canonical_description')) //check the column
            {
                $table->string('canonical_description', 255)->nullable()->change();
            }
        });
    }
};
