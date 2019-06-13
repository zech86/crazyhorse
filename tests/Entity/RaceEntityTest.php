<?php

namespace App\Tests\Entity;

use App\Domain\Horse\Endurance;
use App\Domain\Horse\Speed;
use App\Domain\Horse\Stats;
use App\Domain\Horse\Strength;
use App\Domain\Seconds;
use App\Entity\Horse;
use App\Entity\Jockey;
use App\Entity\Race;
use PHPUnit\Framework\TestCase;

class RaceEntityTest extends TestCase
{
    public function testIntegrationShouldCreateWithMax()
    {
        $race = Race::withRandomHorseCandidates(10);
        $this->assertCount(10, $race->candidates());
    }

    public function testIntegrationShouldHaveWinner()
    {
        $horse1 = new Horse(
            new Jockey(),
            new Stats(new Speed(51), new Strength(100), new Endurance(15))
        );

        $horse2 = new Horse(
            new Jockey(),
            new Stats(new Speed(45), new Strength(100), new Endurance(15))
        );

        $horse3 = new Horse(
            new Jockey(),
            new Stats(new Speed(50), new Strength(100), new Endurance(15))
        );

        $race = new Race($horse1, $horse2, $horse3);

        $race->advance(new Seconds(30));
        $top = $race->getTop(3);

        $this->assertCount(2, $top);

        $this->assertEquals($horse1->name(), $top[0]->horse()->name());
        $this->assertEquals($horse3->name(), $top[1]->horse()->name());
        $this->assertFalse($race->finished());

        $race->advance(new Seconds(50));
        $top = $race->getTop(3);

        $this->assertTrue($race->finished());
        $this->assertCount(3, $top);
        $this->assertEquals($horse1->name(), $top[0]->horse()->name());
        $this->assertEquals($horse3->name(), $top[1]->horse()->name());
        $this->assertEquals($horse2->name(), $top[2]->horse()->name());
    }
}