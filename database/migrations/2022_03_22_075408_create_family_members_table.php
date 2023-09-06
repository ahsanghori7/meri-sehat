<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamilyMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('family_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name', 100);
            $table->string('phone', 20);
            $table->string('email', 50);
            $table->string('image')->nullable();
            $table->string('relationship', 50);
            $table->string('gender', 50)->default('male');
            $table->string('height', 50)->nullable();
            $table->string('weight', 50)->nullable();
            $table->date('birth_date')->formate('Y-m-d')->nullable();
            $table->boolean('status')->default(true);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('family_members');
    }
}
