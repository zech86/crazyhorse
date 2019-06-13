<?php

namespace App\Domain\Horse;

use App\Domain\RandomRangeFloat;

final class Strength
{
    /** @var float */
    private $strength;

    public function __construct(float $strength)
    {
        $this->strength = round($strength, 10);
    }

    public static function createFromRandomRange(float $min, float $max)
    {
        return new self((new RandomRangeFloat($min, $max))->value());
    }

    public function value(): float
    {
        return $this->strength;
    }
}