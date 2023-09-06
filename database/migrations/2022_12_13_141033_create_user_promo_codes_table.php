<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_promo_codes', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('promo_code_id');
            $table->integer('no_of_consultation_avail')->default(0);
            $table->integer('no_of_sehat_scan_avail')->default(0);
            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('user_promo_codes');
    }
}
