<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsDescriptionShowAndWrittenByColumnInPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->bigInteger('written_by')->nullable()->unsigned()->index()->after('reviewed_by');
            $table->boolean('is_description_show')->default(false)->after('descripton');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->dropColumn('written_by');
            $table->dropColumn('is_description_show');
        });
    }
}
