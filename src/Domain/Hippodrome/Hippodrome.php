<?php

namespace App\Domain\Hippodrome;

use App\Domain\Seconds;
use App\Entity\HorseCandidate;
use App\Entity\Race;
use App\Repository\RaceRepository;

final class Hippodrome
{
    private const MAX_ALLOWED_AT_SAME_TIME = 3;
    private const HORSES_PER_RACE = 8;
    private const SECONDS_TO_ADVANCE = 10;
    private const MAX_FINISHED_VISIBLE = 5;
    private const MAX_TOP_PER_RACE = 3;

    /** @var RaceRepository */
    protected $raceRepository;

    public function __construct(RaceRepository $raceRepository)
    {
        $this->raceRepository = $raceRepository;
    }

    public function startNewRace(): Race
    {
        $notFinished = $this->raceRepository->getCountByNotFinished();

        if ($notFinished > self::MAX_ALLOWED_AT_SAME_TIME) {
            throw new \BadMethodCallException('Not allowed');
        }

        $race = Race::withRandomHorseCandidates(self::HORSES_PER_RACE);
        $this->raceRepository->save($race);

        return $race;
    }

    public function advanceRace(Race $race): void
    {
        $seconds = new Seconds(self::SECONDS_TO_ADVANCE);
        $race->advance($seconds);
        $this->raceRepository->save($race);
    }

    public function stateAsArray(): array
    {
        $notFinished = $this->raceRepository->findNotFinishedRaces();
        $notFinished = array_map(function (Race $race) {
            return $race->stateAsArray();
        }, $notFinished);

        $finished = $this->raceRepository->findFinishedRaces(self::MAX_FINISHED_VISIBLE);
        $finished = array_map(function (Race $race) {
            $state = $race->stateAsArray();
            $state['candidates'] = array_map(function (HorseCandidate $candidate) {
                return $candidate->stateAsArray();
            }, $race->getTop(self::MAX_TOP_PER_RACE));

            return $state;
        }, $finished);

        $candidate = $this->raceRepository->findBestCandidateEver();
        $bestEver = [];

        if ($candidate instanceof HorseCandidate) {
            $bestEver = $candidate->stateAsArray();
            $bestEver['stats'] = $candidate->horse()->stats()->toArray();
        }

        return [
            'best_ever' => $bestEver,
            'finished' => $finished,
            'not_finished' => $notFinished,
        ];
    }
}