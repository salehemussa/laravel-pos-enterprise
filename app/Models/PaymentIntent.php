<?php

namespace App\Modules\Payments\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Sale;
use App\Models\User;

class PaymentIntent extends Model
{
    protected $table = 'payment_intents';

    protected $fillable = [
        'sale_id',
        'provider',
        'amount_cents',
        'status',
        'reference',
        'provider_reference',
        'initiated_by',
        'meta',
    ];

    protected $casts = [
        'amount_cents' => 'integer',
        'meta' => 'array',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function initiator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'initiated_by');
    }
}
