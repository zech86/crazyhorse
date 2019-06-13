<?php

namespace App\Tests\Domain;

use App\Domain\Distance;
use App\Domain\Horse\Speed;
use App\Domain\Seconds;
use PHPUnit\Framework\TestCase;

class DistanceTest extends TestCase
{
    public function testShouldIncreaseDistance()
    {
        $old = new Distance(1);
        $new = $old->increase(new Speed(4), new Seconds(3));

        $this->assertIsFloat($old->value());
        $this->assertIsFloat($new->value());

        $this->assertEquals(1, $old->value());
        $this->assertEquals(13, $new->value());
    }
}