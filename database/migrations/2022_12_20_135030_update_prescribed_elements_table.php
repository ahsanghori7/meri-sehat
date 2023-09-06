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
            if (!Schema::hasColumn('prescribed_elements', 'afternoon')) //check the column
            {
                $table->string('afternoon', 100)->nullable();
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
            $table->dropColumn('afternoon');
        });
    }
};
