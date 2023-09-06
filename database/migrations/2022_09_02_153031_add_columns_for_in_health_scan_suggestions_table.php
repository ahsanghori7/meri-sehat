<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsForInHealthScanSuggestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('health_scan_suggestions', function (Blueprint $table) {
            $table->text('urdu_description')->nullable()->after('description');
            $table->text('urdu_diagnosis')->nullable()->after('diagnosis');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('health_scan_suggestions', function (Blueprint $table) {
            $table->dropColumn('urdu_description');
            $table->dropColumn('urdu_diagnosis');
        });
    }
}
