<?php

namespace App\Domain\Horse;

class Stats
{
    private const BASE_SPEED_IN_METER_PER_SECOND = 5;
    private const BASE_JOCKEY_RESISTANCE_IN_METER_PER_SECOND = 5;
    private const BASE_JOCKEY_RESISTANCE_FACTOR = 8;

    /** @var Speed */
    private $speed;

    /** @var Strength */
    private $strength;

    /** @var Endurance */
    private $endurance;

    /** @var bool */
    private $decreased;

    public function __construct(Speed $speed, Strength $strength, Endurance $endurance, bool $decreased = false)
    {
        $this->speed = $speed;
        $this->strength = $strength;
        $this->endurance = $endurance;
        $this->decreased = $decreased;
    }

    public static function fromArray(array $data)
    {
        return new self(
            new Speed((float) $data['speed']),
            new Strength((float) $data['strength']),
            new Endurance((float) $data['endurance']),
            (bool) $data['decreased']
        );
    }

    public static function default()
    {
        $speed = Speed::createFromRandomRange(0, 10);
        $speed = $speed->increase(new Speed(self::BASE_SPEED_IN_METER_PER_SECOND));

        return new self(
            $speed,
            Strength::createFromRandomRange(0, 10),
            Endurance::createFromRandomRange(0, 10)
        );
    }

    public function decreaseSpeed(): Stats
    {
        if ($this->decreased) {
            return $this;
        }

        $stats = new self($this->speed, $this->strength, $this->endurance, true);

        $percentage = $stats->strength->value() * self::BASE_JOCKEY_RESISTANCE_FACTOR;
        $percentage = $percentage / 100;
        $resistance = self::BASE_JOCKEY_RESISTANCE_IN_METER_PER_SECOND;
        $speed = ($resistance * $percentage) - $resistance;

        $stats->speed = $stats->speed->decrease(new Speed($speed));

        return $stats;
    }

    public function speed(): Speed
    {
        return $this->speed;
    }

    public function strength(): Strength
    {
        return $this->strength;
    }

    public function endurance(): Endurance
    {
        return $this->endurance;
    }

    public function toArray(): array
    {
        return [
            'speed' => $this->speed->value(),
            'strength' => $this->strength->value(),
            'endurance' => $this->endurance->value(),
            'decreased' => $this->decreased
        ];
    }
}