<?php

namespace App\Modules\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $table = 'payment_transactions';

    protected $fillable = [
        'payment_intent_id',
        'provider',
        'status',
        'provider_txn_id',
        'event_id',
        'amount_cents',
        'payload',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'payload' => 'array',
    ];

    public function intent(): BelongsTo
    {
        return $this->belongsTo(PaymentIntent::class, 'payment_intent_id');
    }
}
