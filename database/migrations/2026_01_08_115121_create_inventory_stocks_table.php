<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('inventory_stocks', function (Blueprint $table) {
            $table->id();

            // Each product has ONE current stock row
            $table->foreignId('product_id')->unique()->constrained('products')->cascadeOnDelete();

            // Stock quantity in integer units (pcs, bottles, etc.)
            $table->integer('quantity')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_stocks');
    }
};
