<?php

namespace App\Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use App\Modules\Payments\Http\Requests\InitiatePaymentRequest;
use App\Modules\Payments\DTOs\InitiatePaymentData;
use App\Modules\Payments\Actions\InitiatePaymentAction;
use App\Modules\Payments\Http\Resources\PaymentIntentResource;
use App\Modules\Payments\Models\PaymentIntent;

class PaymentController extends Controller
{
    public function initiate(InitiatePaymentRequest $request, InitiatePaymentAction $action): JsonResponse
    {
        $this->authorize('payments.initiate');

        $intent = $action->execute(
            InitiatePaymentData::fromRequest($request),
            (int) auth('api')->id()
        );

        return response()->json([
            'message' => 'Payment initiated.',
            'data' => new PaymentIntentResource($intent),
        ], 201);
    }

    public function showIntent(string $reference): JsonResponse
    {
        $this->authorize('payments.view');

        $intent = PaymentIntent::query()->where('reference', $reference)->firstOrFail();

        return response()->json([
            'data' => new PaymentIntentResource($intent),
        ]);
    }
}
