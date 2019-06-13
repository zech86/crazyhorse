<?php

namespace App\Domain;

final class Seconds
{
    /** @var float */
    private $seconds;

    public function __construct(float $seconds)
    {
        $this->seconds = round($seconds, 10);
    }

    public function value(): float
    {
        return $this->seconds;
    }

    public function increase(Seconds $seconds): Seconds
    {
        return new self($seconds->value() + $this->seconds);
    }
}