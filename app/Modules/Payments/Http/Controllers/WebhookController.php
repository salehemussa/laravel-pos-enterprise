<?php

namespace App\Modules\Payments\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

use App\Modules\Payments\Actions\HandleWebhookAction;
use App\Modules\Payments\Integrations\Contracts\PaymentGateway;
use App\Modules\Payments\Integrations\Mpesa\MpesaGateway;
use App\Modules\Payments\Integrations\TigoPesa\TigoPesaGateway;
use App\Modules\Payments\Integrations\AirtelMoney\AirtelMoneyGateway;

class WebhookController extends Controller
{
    public function handle(string $provider, Request $request, HandleWebhookAction $action): JsonResponse
    {
        $gateway = $this->resolveGateway($provider);

        $result = $action->execute(
            $provider,
            $request->headers->all(),
            $request->all(),
            $gateway
        );

        // Providers usually expect 200 OK quickly
        return response()->json(['ok' => true] + $result);
    }

    private function resolveGateway(string $provider): PaymentGateway
    {
        return match ($provider) {
            'mpesa' => app(MpesaGateway::class),
            'tigopesa' => app(TigoPesaGateway::class),
            'airtelmoney' => app(AirtelMoneyGateway::class),
            default => throw ValidationException::withMessages(['provider' => 'Unknown provider.']),
        };
    }
}
