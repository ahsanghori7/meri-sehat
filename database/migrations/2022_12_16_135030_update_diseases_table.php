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
        //
        Schema::table('diseases', function (Blueprint $table) {
            if (!Schema::hasColumn('diseases', 'approved_by')) //check the column
            {
                $table->unsignedBigInteger('approved_by')->nullable();
                $table->foreign('approved_by')->references('id')->on('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('diseases', 'written_by')) //check the column
            {
                $table->unsignedBigInteger('written_by')->nullable();
                $table->foreign('written_by')->references('id')->on('users')->onDelete('cascade');
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
        //
        Schema::table('diseases', function (Blueprint $table) {
            $table->dropColumn('approved_by');
            $table->dropColumn('written_by');
        });
    }
};
