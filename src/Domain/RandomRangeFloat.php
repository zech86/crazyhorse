<?php

namespace App\Domain;

final class RandomRangeFloat
{
    /** @var float */
    private $value;

    public function __construct(float $min, float $max)
    {
        $random = $min;
        $random = $random + (mt_rand() / mt_getrandmax());
        $random = $random * ($max - $min);

        $this->value = round($random, 10);
    }

    public function value(): float
    {
        return $this->value;
    }
}