<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateWidgetTopicPillsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_topic_pills', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable()->unsigned()->index()->change();
            $table->string('type')->nullable()->default('topic')->after('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('widget_topic_pills', function (Blueprint $table) {
            $table->bigInteger('parent_id')->nullable()->unsigned()->index()->change();
            $table->dropColumn('type');
        });
    }
}
