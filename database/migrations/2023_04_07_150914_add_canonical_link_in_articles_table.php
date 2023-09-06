<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanonicalLinkInArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql3')->table('articles', function (Blueprint $table) {
            if (!Schema::connection('mysql3')->hasColumn('articles', 'canonical_link')){
                $table->string('canonical_link')->nullable();
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
        Schema::connection('mysql3')->table('articles', function (Blueprint $table) {
            if (Schema::connection('mysql3')->hasColumn('articles', 'canonical_link')){
                $table->dropColumn('canonical_link');
            }
        });
    }
}
