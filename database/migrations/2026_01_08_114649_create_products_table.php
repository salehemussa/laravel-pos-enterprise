<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Basic product identity
            $table->string('name', 150);
            $table->string('sku', 60)->unique(); // SKU used in POS scanning/searching
            $table->text('description')->nullable();

            // Pricing: store as integers (cents) for accuracy (no floating point issues)
            $table->unsignedBigInteger('price_cents'); // selling price
            $table->unsignedBigInteger('cost_cents')->nullable(); // optional, for profit reports

            // Status (active/inactive) - soft disable without deleting
            $table->boolean('is_active')->default(true);

            // Optional: track who created it
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
