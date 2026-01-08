<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sale_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();
            $table->foreignId('product_id')->constrained('products')->restrictOnDelete();

            // Quantity sold
            $table->integer('quantity');

            // Snapshot pricing (important: product price can change later)
            $table->unsignedBigInteger('unit_price_cents'); // selling unit price at time of sale
            $table->unsignedBigInteger('line_subtotal_cents')->default(0); // qty * unit
            $table->unsignedBigInteger('discount_cents')->default(0); // per line
            $table->unsignedBigInteger('tax_cents')->default(0); // per line (optional)
            $table->unsignedBigInteger('line_total_cents')->default(0); // subtotal - discount + tax

            // Optional: store product name/sku snapshot for receipts (helps if product deleted/renamed)
            $table->string('product_name', 150);
            $table->string('product_sku', 60);

            $table->timestamps();

            $table->index(['sale_id']);
            $table->index(['product_id']);
            $table->index(['sale_id', 'product_id']); // fast lookups
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_items');
    }
};
