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
        //
        Schema::table('footers', function (Blueprint $table) {
            if (!Schema::hasColumn('parent_id', 'type', 'col_width', 'target', 'image')) //check the column
            {
                $table->integer('parent_id')->nullable();
                $table->string('type',50);
                $table->integer('col_width')->nullable();
                $table->string('target')->nullable();
                $table->string('image', 255)->nullable();
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
        //
        Schema::table('footers', function (Blueprint $table) {
            $table->dropColumn('parent_id');
            $table->dropColumn('type');
            $table->dropColumn('col_width');
            $table->dropColumn('target');
            $table->dropColumn('image');
        });
    }
};
