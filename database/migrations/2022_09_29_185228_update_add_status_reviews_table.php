<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddStatusReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('reviews', function (Blueprint $table) {
            $table->enum('status', ['pending','approved', 'disapproved'])->default('pending')->after('description');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
