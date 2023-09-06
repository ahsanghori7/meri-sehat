<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymobResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::connection('mysql')->hasTable('paymob_responses')){
            Schema::create('paymob_responses', function (Blueprint $table) {
                $table->id();
                $table->integer('transaction_id');
                $table->longText('response')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::connection('mysql')->hasTable('paymob_responses')){
            Schema::dropIfExists('paymob_responses');
        }
    }
}
