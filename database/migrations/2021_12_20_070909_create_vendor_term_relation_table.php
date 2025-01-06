<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorTermRelationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendortermrelation', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id')->nullable();
            $table->integer('subcategory_id')->nullable();
            $table->integer('type_id')->nullable();
            $table->integer('year_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->integer('vendor_term_id')->nullable();
            $table->integer('invoice_count')->nullable();
            $table->integer('invoice_max_count')->nullable();
            $table->integer('invoice_count')->nullable();
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
        Schema::dropIfExists('vendortermrelation');
    }
}
