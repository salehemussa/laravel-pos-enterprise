<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();

            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();

            // Types: IN (add), OUT (remove), ADJUST (set/adjust)
            $table->string('type', 20);

            // Positive integer quantity moved
            $table->integer('quantity');

            // Optional reason/notes (e.g. "Initial stock", "Damaged", "Sale #123")
            $table->string('reason', 190)->nullable();

            // For auditing: who performed the movement
            $table->foreignId('performed_by')->nullable()->constrained('users')->nullOnDelete();

            // Optional reference to external entity (sale_id, purchase_id, etc.) for traceability
            $table->string('reference_type', 50)->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();

            $table->timestamps();

            $table->index(['product_id', 'type']);
            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
