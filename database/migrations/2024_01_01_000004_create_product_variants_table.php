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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->string('sku')->unique();
            $table->string('title');
            $table->decimal('price', 10, 2);
            $table->decimal('compare_price', 10, 2)->nullable();
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->integer('quantity')->default(0);
            $table->integer('min_quantity')->default(1);
            $table->decimal('weight', 8, 2)->nullable();
            $table->json('dimensions')->nullable();
            $table->string('barcode')->nullable();
            $table->string('option1')->nullable(); // e.g., Size
            $table->string('value1')->nullable(); // e.g., 0-3 months
            $table->string('option2')->nullable(); // e.g., Color
            $table->string('value2')->nullable(); // e.g., Pink
            $table->string('option3')->nullable();
            $table->string('value3')->nullable();
            $table->string('image')->nullable();
            $table->boolean('requires_shipping')->default(true);
            $table->boolean('taxable')->default(true);
            $table->decimal('tax_rate', 5, 2)->default(0.00);
            $table->string('position')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
