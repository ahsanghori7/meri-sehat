<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('article_labels', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->unsignedBigInteger('article_fact_id')->nullable()->after('article_id');
            $table->foreign('article_fact_id')->references('id')->on('article_facts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('article_labels', function (Blueprint $table) {
            $table->string('name', 100)->after('article_id');
            $table->dropColumn('article_fact_id');
        });
    }
}
