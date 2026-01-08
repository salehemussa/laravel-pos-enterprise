<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_intents', function (Blueprint $table) {
            $table->id();

            // We can attach to a sale (POS receipt) or other entity later
            $table->foreignId('sale_id')->constrained('sales')->cascadeOnDelete();

            // Provider info: cash|card|mpesa|tigopesa|airtelmoney
            $table->string('provider', 30);

            // amount requested
            $table->unsignedBigInteger('amount_cents');

            // status lifecycle
            // pending -> processing -> succeeded | failed | cancelled | expired
            $table->string('status', 20)->default('pending');

            // internal unique reference for this intent (safe to share with client)
            $table->string('reference', 60)->unique();

            // provider-specific reference ids (e.g. checkout_request_id)
            $table->string('provider_reference', 120)->nullable();

            // who initiated (cashier)
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();

            // optional metadata (JSON) for channel, phone, etc.
            $table->json('meta')->nullable();

            $table->timestamps();

            $table->index(['sale_id', 'status']);
            $table->index(['provider', 'provider_reference']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_intents');
    }
};
