<?php

namespace App\Modules\Payments\Actions;

use App\Models\Sale;

class ApplyPaymentToSaleAction
{
    /**
     * Apply money to a sale and compute payment_status.
     * This is isolated so multiple providers can reuse it.
     */
    public function execute(Sale $sale, int $amountCents): Sale
    {
        $paid = (int) $sale->paid_cents + $amountCents;
        $total = (int) $sale->total_cents;

        $balance = max(0, $total - $paid);

        $paymentStatus = 'partial';
        if ($paid <= 0) $paymentStatus = 'unpaid';
        if ($balance === 0 && $total > 0) $paymentStatus = 'paid';

        $sale->update([
            'paid_cents' => $paid,
            'balance_cents' => $balance,
            'payment_status' => $paymentStatus,
        ]);

        return $sale->refresh();
    }
}
