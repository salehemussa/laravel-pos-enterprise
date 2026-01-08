<?php

namespace App\Shared\ValueObjects;

use InvalidArgumentException;

final class Quantity
{
    private int $value;

    public function __construct(int $value)
    {
        if ($value < 0) {
            throw new InvalidArgumentException('Quantity cannot be negative.');
        }

        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function add(int $qty): self
    {
        return new self($this->value + $qty);
    }

    public function subtract(int $qty): self
    {
        if ($qty > $this->value) {
            throw new InvalidArgumentException('Insufficient quantity.');
        }

        return new self($this->value - $qty);
    }
}
