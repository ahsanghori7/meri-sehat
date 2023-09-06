<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name', 100)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('network', 50)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('otp', 10)->nullable();
            $table->string('password')->nullable();
            $table->string('new_phone', 50)->nullable();
            $table->string('image')->nullable();
            $table->string("gender")->default('male');
            $table->string('height', 20)->nullable();
            $table->string('weight', 20)->nullable();
            $table->date("birth_date")->formate('Y-m-d')->nullable();
            $table->boolean("is_subscribed")->default(0);
            $table->boolean("is_phone_verified")->default(0);
            $table->boolean('status')->default(1);
            // ------- Start Extra fields which used in admin panel ------ //
            $table->string('language', 20)->nullable();
            $table->string('social_profile_type')->nullable();
            $table->string('social_profile_id')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            // ------- End Extra fields which used in admin panel ------ //
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
