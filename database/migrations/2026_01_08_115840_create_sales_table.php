<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();

            // Sale identity (human-friendly code/receipt number)
            $table->string('sale_no', 40)->unique(); // e.g. POS-2026-000001

            // Who performed the sale (cashier/seller)
            $table->foreignId('sold_by')->constrained('users')->cascadeOnDelete();

            // Optional: customer (if you have customers in users table)
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();

            // Money fields stored in cents (avoid float errors)
            $table->unsignedBigInteger('subtotal_cents')->default(0);
            $table->unsignedBigInteger('discount_cents')->default(0);
            $table->unsignedBigInteger('tax_cents')->default(0);
            $table->unsignedBigInteger('total_cents')->default(0);

            // Payment tracking
            $table->string('payment_status', 20)->default('unpaid'); // unpaid|partial|paid|voided
            $table->unsignedBigInteger('paid_cents')->default(0);
            $table->unsignedBigInteger('balance_cents')->default(0);

            // Sale status (business state)
            $table->string('status', 20)->default('completed'); // draft|completed|voided|refunded

            // Notes / metadata
            $table->string('currency', 10)->default('TZS'); // helps if later add USD
            $table->string('notes', 190)->nullable();

            $table->timestamps();

            $table->index(['sold_by', 'created_at']);
            $table->index(['payment_status']);
            $table->index(['status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
