<?php

namespace App\Modules\Payments\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentIntentResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'reference' => $this->reference,
            'sale_id' => $this->sale_id,
            'provider' => $this->provider,
            'amount_cents' => $this->amount_cents,
            'amount' => $this->amount_cents / 100,
            'status' => $this->status,
            'provider_reference' => $this->provider_reference,
            'meta' => $this->meta,
            'created_at' => optional($this->created_at)->toISOString(),
        ];
    }
}
