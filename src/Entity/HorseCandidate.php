<?php

namespace App\Entity;

use App\Domain\Distance;
use App\Domain\Seconds;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HorseCandidateRepository")
 * @ORM\Table(name="horse_candidate")
 */
class HorseCandidate
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="Horse", cascade={"persist"})
     */
    private $horse;

    /**
     * @ORM\ManyToOne(targetEntity="Race", cascade={"all"})
     */
    private $race;

    /**
     * @ORM\Column(type="distance")
     */
    private $distanceRan;

    /**
     * @ORM\Column(type="seconds", nullable=true)
     */
    private $finishedAt;

    /**
     * @ORM\Column(type="seconds")
     */
    private $time;

    public function __construct(Race $race, Horse $horse)
    {
        $this->race = $race;
        $this->horse = $horse;
        $this->distanceRan = new Distance(0);
        $this->time = new Seconds(0);
        $this->finishedAt = new Seconds(0);
    }

    public function advance(Seconds $seconds): self
    {
        if ($seconds->value() <= 0) {
            return $this;
        }

        $advance = function (HorseCandidate $candidate, Seconds $seconds) {
            $milliseconds = $seconds->value() * 100;

            for ($i = $milliseconds; $i > 0; $i--) {
                if ($candidate->finished()) {
                    break;
                }

                $time = new Seconds(0.01);

                $candidate->distanceRan = $candidate->distanceRan->increase(
                    $candidate->horse->stats()->speed(),
                    $time
                );

                if ($candidate->distanceRan->value()
                    > $candidate->horse->stats()->endurance()->valueInMeters()
                ) {
                    $candidate->horse->decreaseSpeed();
                }

                $candidate->time = $candidate->time->increase($time);

                if ($candidate->distanceRan->value() >= $candidate->race->limit()) {
                    $candidate->finishedAt = new Seconds($candidate->time->value());
                }
            }
        };

        $advance($this, $seconds);

        return $this;
    }

    public function horse(): Horse
    {
        return $this->horse;
    }

    public function finished(): bool
    {
        return $this->finishedAt->value() != 0;
    }

    public function finishedAt(): float
    {
        if (!$this->finished()) {
            throw new \BadMethodCallException('Horse has not finished');
        }

        return $this->finishedAt->value();
    }

    public function distanceRan(): float
    {
        return $this->distanceRan->value();
    }

    public function stateAsArray(): array
    {
        return [
            'name' => $this->horse->name(),
            'distance_ran' => $this->distanceRan(),
            'finished_at' => $this->finished() ? $this->finishedAt() : 0,
        ];
    }
}