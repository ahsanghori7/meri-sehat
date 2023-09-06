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
        Schema::table('prescribed_elements', function (Blueprint $table) {
            if (!Schema::hasColumn('prescribed_elements', 'unit')) //check the column
            {
                $table->string('unit', 100)->nullable();
            }

            if (!Schema::hasColumn('prescribed_elements', 'morning')) //check the column
            {
                $table->string('morning', 100)->nullable();
            }

            if (!Schema::hasColumn('prescribed_elements', 'evening')) //check the column
            {
                $table->string('evening', 100)->nullable();
            }
            if (!Schema::hasColumn('prescribed_elements', 'night')) //check the column
            {
                $table->string('night', 100)->nullable();
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
        Schema::table('prescribed_elements', function (Blueprint $table) {
            $table->dropColumn('unit');
            $table->dropColumn('morning');
            $table->dropColumn('evening');
            $table->dropColumn('night');
        });
    }
};
