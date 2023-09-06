<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAddBloodGroupFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('family_members', function (Blueprint $table) {
            $table->string('blood_group')->after('birth_date');
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
        Schema::table('family_members', function (Blueprint $table) {
            $table->dropColumn('blood_group');
        });
    }
}
