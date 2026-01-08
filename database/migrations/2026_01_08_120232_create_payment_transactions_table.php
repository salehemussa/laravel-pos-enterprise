<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_intent_id')->constrained('payment_intents')->cascadeOnDelete();

            // provider (repeat for easy querying)
            $table->string('provider', 30);

            // success|failed|pending
            $table->string('status', 20)->default('pending');

            // provider's transaction id (e.g. receipt number)
            $table->string('provider_txn_id', 120)->nullable();

            // idempotency key: unique event id from provider (VERY IMPORTANT)
            // If provider doesn't give one, we can hash payload.
            $table->string('event_id', 120)->unique();

            // amount captured/paid (can be partial)
            $table->unsignedBigInteger('amount_cents')->default(0);

            // raw payload for audit/debug (JSON)
            $table->json('payload')->nullable();

            $table->timestamps();

            $table->index(['provider', 'provider_txn_id']);
            $table->index(['payment_intent_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
