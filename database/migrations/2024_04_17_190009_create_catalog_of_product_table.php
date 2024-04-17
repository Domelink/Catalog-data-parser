<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('catalog_of_product', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('type');
            $table->string('category_uuid');
            $table->string('manufacturer');
            $table->string('name');
            $table->string('cod_of_model');
            $table->string('description');
            $table->float('price');
            $table->integer('guaranty')->nullable();
            $table->boolean('availability');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catalog_of_product');
    }
};
