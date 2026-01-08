<?php

namespace App\Shared\Helpers;

class MoneyHelper
{
    public static function toCents(float $amount): int
    {
        return (int) round($amount * 100);
    }

    public static function fromCents(int $cents): float
    {
        return $cents / 100;
    }
}
