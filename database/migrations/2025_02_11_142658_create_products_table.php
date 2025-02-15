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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignId('product_type_id')->constrained();
            $table->string('brand');
            $table->string('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('discounted_price_1', 10, 2)->nullable();
            $table->decimal('discounted_price_2', 10, 2)->nullable();
            $table->decimal('discounted_price_3', 10, 2)->nullable();
            $table->decimal('purchase_wholesale_price', 10, 2)->nullable();
            $table->integer('stocks')->default(0);
            $table->dateTime('closest_expiration_date')->nullable();
            $table->boolean('isArchived')->default(false);
            $table->timestamps();
        });

        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('type_name');
            $table->string('description')->nullable();
            $table->boolean('isArchived')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_types');
    }
};
