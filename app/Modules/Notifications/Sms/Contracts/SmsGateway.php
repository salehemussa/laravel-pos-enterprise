<?php

namespace App\Modules\Notifications\Sms\Contracts;

interface SmsGateway
{
    /**
     * Send an SMS message.
     * Implementations handle provider-specific HTTP/API calls.
     */
    public function send(string $to, string $message): void;
}
