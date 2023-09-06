<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableInAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropForeign('doctor_id');
            $table->integer('doctor_id')->unsigned()->nullable()->change();
            $table->date('date')->formate('Y-m-d')->nullable()->change();
            $table->time('time')->formate('H:m')->nullable()->change();
            $table->string('reason')->nullable()->change();
            $table->integer('consultation_fee')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->integer('doctor_id')->unsigned()->nullable(false)->change();
            $table->date('date')->formate('Y-m-d')->nullable(false)->change();
            $table->time('time')->formate('H:m')->nullable(false)->change();
            $table->string('reason')->nullable()->change(false);
        });
    }
}
