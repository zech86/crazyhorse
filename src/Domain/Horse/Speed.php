<?php

namespace App\Domain\Horse;

use App\Domain\RandomRangeFloat;

final class Speed
{
    /** @var float */
    private $speed;

    public function __construct(float $speed)
    {
        $this->speed = round($speed, 10);
    }

    public static function createFromRandomRange(float $min, float $max): Speed
    {
        return new self((new RandomRangeFloat($min, $max))->value());
    }

    public function value(): float
    {
        return $this->speed;
    }

    public function increase(Speed $speed): Speed
    {
        return new self($this->speed + $speed->value());
    }

    public function decrease(Speed $speed): Speed
    {
        return new self($this->speed - $speed->value());
    }
}