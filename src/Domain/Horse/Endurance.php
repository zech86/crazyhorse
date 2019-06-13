<?php
namespace App\Domain\Horse;

use App\Domain\RandomRangeFloat;

final class Endurance
{
    /** @var float */
    private $endurance;

    public function __construct(float $endurance)
    {
        $this->endurance = round($endurance, 10);
    }

    public static function createFromRandomRange(float $min, float $max)
    {
        return new self((new RandomRangeFloat($min, $max))->value());
    }

    public function value(): float
    {
        return $this->endurance;
    }

    public function valueInMeters(): float
    {
        return $this->endurance * 100;
    }
}