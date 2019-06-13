<?php

namespace App\Tests\Domain;

use App\Domain\Horse\Endurance;
use App\Domain\Horse\Speed;
use App\Domain\Horse\Stats;
use App\Domain\Horse\Strength;
use PHPUnit\Framework\TestCase;

class StatsTest extends TestCase
{
    public function testShouldDecreaseSpeed()
    {
        $old = new Stats(new Speed(6), new Strength(1), new Endurance(1));
        $new = $old->decreaseSpeed();

        $this->assertEquals(6, $old->speed()->value());
        $this->assertIsFloat($new->speed()->value());
        $this->assertNotEquals(6, $new->speed()->value());
    }
}