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
        Schema::table('doctor_condition', function (Blueprint $table) {
            if(!Schema::hasColumn('doctor_condition', 'service_id')){
                $table->unsignedBigInteger('service_id');
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
        Schema::table('doctor_condition', function (Blueprint $table) {
            if(Schema::hasColumn('doctor_condition', 'service_id')){
                $table->dropColumn('service_id');
            }
        });
    }
};
