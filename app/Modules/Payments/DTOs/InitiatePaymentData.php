<?php

namespace App\Modules\Payments\DTOs;

use App\Modules\Payments\Http\Requests\InitiatePaymentRequest;

final class InitiatePaymentData
{
    public function __construct(
        public readonly int $saleId,
        public readonly string $provider,
        public readonly float $amount,
        public readonly ?string $phone,
    ) {}

    public static function fromRequest(InitiatePaymentRequest $request): self
    {
        $d = $request->validated();

        return new self(
            saleId: (int) $d['sale_id'],
            provider: $d['provider'],
            amount: (float) $d['amount'],
            phone: $d['phone'] ?? null
        );
    }
}
