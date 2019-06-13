<?php

namespace App\Tests\Domain;

use App\Domain\Horse\Strength;
use PHPUnit\Framework\TestCase;

class StrengthTest extends TestCase
{
    public function testShouldReturnFloat()
    {
        $old = new Strength(0.34);
        $this->assertIsFloat($old->value());
        $this->assertEquals(0.34, $old->value());
    }
}