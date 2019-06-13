<?php

namespace App\Tests\Domain;

use App\Domain\Horse\Endurance;
use PHPUnit\Framework\TestCase;

class EnduranceTest extends TestCase
{
    public function testShouldReturnFloat()
    {
        $old = new Endurance(0.34);
        $this->assertIsFloat($old->value());
        $this->assertEquals(0.34, $old->value());
    }
}