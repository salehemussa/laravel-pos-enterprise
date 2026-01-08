<?php

namespace App\Modules\Reports\Http\Requests;

class SalesSummaryRequest extends DateRangeRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            // Filter by seller/cashier
            'sold_by' => ['sometimes', 'integer', 'exists:users,id'],

            // Filter by payment status
            'payment_status' => ['sometimes', 'in:unpaid,partial,paid,voided'],

            // Filter by sale status
            'status' => ['sometimes', 'in:draft,completed,voided,refunded'],
        ]);
    }
}
