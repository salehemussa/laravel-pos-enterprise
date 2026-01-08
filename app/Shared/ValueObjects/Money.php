<?php

namespace App\Shared\ValueObjects;

use InvalidArgumentException;

final class Money
{
    private int $cents;
    private string $currency;

    public function __construct(int $cents, string $currency = 'TZS')
    {
        if ($cents < 0) {
            throw new InvalidArgumentException('Money cannot be negative.');
        }

        $this->cents = $cents;
        $this->currency = $currency;
    }

    public static function fromFloat(float $amount, string $currency = 'TZS'): self
    {
        return new self((int) round($amount * 100), $currency);
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function toFloat(): float
    {
        return $this->cents / 100;
    }

    public function add(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self($this->cents + $other->cents, $this->currency);
    }

    public function subtract(Money $other): self
    {
        $this->assertSameCurrency($other);
        return new self(max(0, $this->cents - $other->cents), $this->currency);
    }

    private function assertSameCurrency(Money $other): void
    {
        if ($this->currency !== $other->currency) {
            throw new InvalidArgumentException('Currency mismatch.');
        }
    }
}
