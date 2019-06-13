<?php

namespace App\Tests\Domain;

use App\Domain\RandomRangeFloat;
use PHPUnit\Framework\TestCase;

class RandomRangeFloatTest extends TestCase
{
    public function testShouldCreateRandomInRange()
    {
        $random = new RandomRangeFloat(0, 1);
        $this->assertGreaterThan(0, $random->value());
        $this->assertLessThan(1, $random->value());
    }
}