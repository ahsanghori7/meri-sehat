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
        Schema::table('health_scans', function (Blueprint $table) {
            if (Schema::hasColumn('health_scans', 'score')) //check the column
            {
                $table->string('score', 2)->default("0")->change();
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
        Schema::table('health_scans', function (Blueprint $table) {
            $table->decimal('score', 2, 1)->default("0.0")->change();
        });
    }
};
