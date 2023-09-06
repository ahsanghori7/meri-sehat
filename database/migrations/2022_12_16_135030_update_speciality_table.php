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
        Schema::table('specialities', function (Blueprint $table) {
            if (!Schema::hasColumn('specialities', 'type')) //check the column
            {
                $table->enum('type', ['doctor', 'wellness-experts'])->default('doctor');
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
        Schema::table('specialities', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
