<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    protected $fillable = [
        'sale_no',
        'sold_by',
        'customer_id',
        'subtotal_cents',
        'discount_cents',
        'tax_cents',
        'total_cents',
        'payment_status',
        'paid_cents',
        'balance_cents',
        'status',
        'currency',
        'notes',
    ];

    protected $casts = [
        'subtotal_cents' => 'integer',
        'discount_cents' => 'integer',
        'tax_cents' => 'integer',
        'total_cents' => 'integer',
        'paid_cents' => 'integer',
        'balance_cents' => 'integer',
    ];

    // --- Relationships ---

    /**
     * Items sold in this sale (line items).
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleItem::class);
    }

    /**
     * Cashier/seller who performed the sale.
     */
    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sold_by');
    }

    /**
     * Customer (optional). Can be null for walk-in customers.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // --- Helpful computed attributes (optional) ---

    /**
     * Example: total in major units for display (TZS).
     * NOTE: keep arithmetic in cents internally for accuracy.
     */
    public function getTotalAttribute(): float
    {
        return $this->total_cents / 100;
    }
}
