<?php

namespace App\Tests\Entity;

use App\Domain\Distance;
use App\Domain\Horse\Endurance;
use App\Domain\Horse\Speed;
use App\Domain\Horse\Stats;
use App\Domain\Horse\Strength;
use App\Domain\Seconds;
use App\Entity\Horse;
use App\Entity\Jockey;
use App\Entity\Race;
use PHPUnit\Framework\TestCase;

class HorseCandidateEntityTest extends TestCase
{
    public function testIntegrationSingleStep()
    {
        $stats = new Stats(new Speed(100), new Strength(100), new Endurance(1500));

        $race = new Race(new Horse(new Jockey(), $stats));
        $candidate = $race->getOrderedCandidates()[0];
        $candidate->advance(new Seconds(15));

        $this->assertEquals(1500, $candidate->distanceRan());
        $this->assertTrue($candidate->finished());
        $this->assertEquals(15, $candidate->finishedAt());
    }

    public function testIntegrationMultipleSteps()
    {
        $stats = new Stats(new Speed(100), new Strength(100), new Endurance(1500));

        $race = new Race(new Horse(new Jockey(), $stats));
        $candidate = $race->getOrderedCandidates()[0];

        $candidate->advance(new Seconds(5));
        $this->assertEquals(500, $candidate->distanceRan());
        $this->assertFalse($candidate->finished());

        $candidate->advance(new Seconds(5));
        $this->assertEquals(1000, $candidate->distanceRan());
        $this->assertFalse($candidate->finished());

        $candidate->advance(new Seconds(5));
        $this->assertEquals(1500, $candidate->distanceRan());
        $this->assertTrue($candidate->finished());
        $this->assertEquals(15, $candidate->finishedAt());
    }

    public function testIntegrationAfterSpeedWasDecreased()
    {
        $stats = new Stats(new Speed(100), new Strength(100), new Endurance(1));

        $race = new Race(new Horse(new Jockey(), $stats));
        $candidate = $race->getOrderedCandidates()[0];

        $candidate->advance(new Seconds(1));

        $this->assertEquals(100, $candidate->distanceRan());
        $this->assertFalse($candidate->finished());

        $candidate->advance(new Seconds(5));
        $this->assertEquals(425.35, $candidate->distanceRan());
        $this->assertFalse($candidate->finished());

        $candidate->advance(new Seconds(9999));
        $this->assertEquals(1500.45, $candidate->distanceRan());
        $this->assertTrue($candidate->finished());
        $this->assertEquals(22.54, $candidate->finishedAt());
    }
}