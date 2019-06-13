<?php

namespace App\Domain;

use App\Domain\Horse\Speed;

final class Distance
{
    /** @var float */
    private $distance;

    public function __construct(float $distance)
    {
        $this->distance = round($distance, 10);
    }

    public function value(): float
    {
        return $this->distance;
    }

    public function increase(Speed $speed, Seconds $seconds): Distance
    {
        // @todo check for conversion
        $distance = $speed->value() * $seconds->value();

        return new self($distance + $this->distance);
    }
}