<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveForignKeyConstraintFromParentIdInWidgetTopicPillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('widget_topic_pills', function (Blueprint $table) {
            $table->dropForeign('widget_topic_pills_topic_id_foreign');
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
            // $table->foreign('widget_topic_pills_topic_id_foreign')->references('id')->on('topics')->onDelete('cascade');
        });
    }
}
