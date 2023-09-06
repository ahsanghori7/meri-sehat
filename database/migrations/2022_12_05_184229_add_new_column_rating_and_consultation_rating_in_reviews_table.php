<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnRatingAndConsultationRatingInReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
            $table->integer('rating');
            $table->string('consultation_rating');
            $table->integer('waiting_time')->unsigned()->nullable()->change();
            $table->integer('bedside_manners')->unsigned()->nullable()->change();
            $table->integer('cleanliness')->unsigned()->nullable()->change();
            $table->integer('staff_friendliness')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('reviews', function (Blueprint $table) {
            //
            $table->dropColumn('rating');
            $table->dropColumn('consultation_rating');
            $table->dropColumn('waiting_time');
            $table->dropColumn('bedside_manners');
            $table->dropColumn('cleanliness');
            $table->dropColumn('staff_friendliness');
        });
    }
}
