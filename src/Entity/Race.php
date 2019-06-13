<?php

namespace App\Entity;

use App\Domain\Distance;
use App\Domain\Seconds;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RaceRepository")
 * @ORM\Table(name="race")
 */
class Race
{
    private const MAX_DISTANCE_IN_METER = 1500.0;

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="HorseCandidate", mappedBy="race", cascade={"all"})
     * @var Collection
     */
    private $candidates = [];

    /**
     * @ORM\Column(type="boolean")
     */
    private $finished;

    /**
     * @ORM\Column(type="seconds")
     */
    private $time;

    /**
     * @ORM\Column(type="distance")
     */
    private $distance;

    public function __construct(Horse...$horses)
    {
        $candidates = array_map(function (Horse $horse) {
            return new HorseCandidate(
                $this,
                $horse
            );
        }, $horses);

        $this->candidates = new ArrayCollection($candidates);
        $this->finished = false;
        $this->time = new Seconds(0);
        $this->distance = new Distance(0);
    }

    public static function withRandomHorseCandidates(int $max): Race
    {
        if ($max <= 0) {
            throw new \BadMethodCallException('A race should start at least with 1 horse');
        }

        $horses = array_map(function () {
            return Horse::default();
        }, range(1, $max));

        return new self(...$horses);
    }

    public function advance(Seconds $seconds): self
    {
        $candidates = $this->candidates->toArray();

        array_walk($candidates, function (HorseCandidate $candidate) use ($seconds) {
            return $candidate->advance($seconds);
        });

        $this->time = $this->time->increase($seconds);

        usort($candidates, function (HorseCandidate $left, HorseCandidate $right) {
            return $left->distanceRan() < $right->distanceRan();
        });

        $this->distance = new Distance($candidates[0]->distanceRan());

        $this->finished();

        return $this;
    }

    public function finished(): bool
    {
        if ($this->finished) {
            return true;
        }

        $candidates = $this->candidates->toArray();
        $finished = array_filter($candidates, function (HorseCandidate $candidate) {
            return $candidate->finished();
        });

        $this->finished = $this->candidates->count() == count($finished);

        return $this->finished;
    }

    public function getTop(int $max)
    {
        $candidates = $this->candidates->toArray();

        $finished = array_filter($candidates, function (HorseCandidate $candidate) {
            return $candidate->finished();
        });

        usort($finished, function (HorseCandidate $left, HorseCandidate $right) {
            return $left->finishedAt() > $right->finishedAt();
        });

        $finished = array_chunk($finished, $max);

        return $finished[0] ?? [];
    }

    /**
     * @return HorseCandidate[]
     */
    public function getOrderedCandidates(): array
    {
        $finished = $this->getTop($this->candidates->count());

        $candidates = $this->candidates->toArray();
        $notFinished = array_filter($candidates, function (HorseCandidate $candidate) {
            return !$candidate->finished();
        });

        usort($notFinished, function (HorseCandidate $left, HorseCandidate $right) {
            return $left->distanceRan() < $right->distanceRan();
        });

        return array_merge($finished, $notFinished);
    }

    public function limit(): float
    {
        return self::MAX_DISTANCE_IN_METER;
    }

    /**
     * @return HorseCandidate[]
     */
    public function candidates(): array
    {
        return $this->candidates->toArray();
    }

    public function stateAsArray(): array
    {
        $candidates = $this->getOrderedCandidates();
        $candidates = array_map(function (HorseCandidate $candidate) {
            return $candidate->stateAsArray();
        }, $candidates);

        return [
            'id' => $this->id,
            'distance' => $this->distance->value(),
            'time' => $this->time->value(),
            'candidates' => $candidates
        ];
    }
}