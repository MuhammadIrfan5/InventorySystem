<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToInventories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->string('current_location')->nullable();
            $table->string('current_consumer')->nullable();
            $table->string('tax')->nullable();
            $table->string('warranty_end')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('inventories', function (Blueprint $table) {
            $table->dropColumn('current_location');
            $table->dropColumn('current_consumer');
            $table->dropColumn('tax');
            $table->dropColumn('warranty_end');
        });
    }
}
