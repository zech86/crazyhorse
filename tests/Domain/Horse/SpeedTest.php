<?php

namespace App\Tests\Domain;

use App\Domain\Horse\Speed;
use PHPUnit\Framework\TestCase;

class SpeedTest extends TestCase
{
    public function testShouldIncreaseAndDecrease()
    {
        $old = new Speed(1);
        $increased = $old->increase(new Speed(1.1));
        $decreased = $old->decrease(new Speed(0.3));

        $this->assertIsFloat($old->value());
        $this->assertIsFloat($increased->value());
        $this->assertIsFloat($decreased->value());

        $this->assertEquals(1, $old->value());
        $this->assertEquals(2.1, $increased->value());
        $this->assertEquals(0.7, $decreased->value());
    }
}