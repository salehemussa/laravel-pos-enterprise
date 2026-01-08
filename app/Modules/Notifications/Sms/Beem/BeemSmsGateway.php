<?php

namespace App\Modules\Notifications\Sms\Beem;

use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;
use App\Modules\Notifications\Sms\Contracts\SmsGateway;

class BeemSmsGateway implements SmsGateway
{
    public function send(string $to, string $message): void
    {
        // NOTE: Put real credentials in .env and config
        $apiKey = config('services.beem.api_key');
        $secret = config('services.beem.secret');
        $sender = config('services.beem.sender_id');

        if (!$apiKey || !$secret || !$sender) {
            throw ValidationException::withMessages([
                'sms' => 'Beem SMS configuration is missing.',
            ]);
        }

        // TODO: Update endpoint/body to match Beem Africa documentation
        $response = Http::withBasicAuth($apiKey, $secret)
            ->post(config('services.beem.endpoint'), [
                'source_addr' => $sender,
                'schedule_time' => '',
                'encoding' => 0,
                'message' => $message,
                'recipients' => [
                    ['recipient_id' => '1', 'dest_addr' => $to],
                ],
            ]);

        if (!$response->successful()) {
            // Log failure for retry / audit
            logger()->error('Beem SMS failed', [
                'to' => $to,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            // Throw to allow queue retry
            throw ValidationException::withMessages([
                'sms' => 'Failed to send SMS.',
            ]);
        }
    }
}
