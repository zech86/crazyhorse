<?php

namespace App\Tests\Domain;

use App\Domain\Seconds;
use PHPUnit\Framework\TestCase;

class SecondsTest extends TestCase
{
    public function testShouldIncrease()
    {
        $old = new Seconds(1);
        $new = $old->increase(new Seconds(0.45));

        $this->assertIsFloat($old->value());
        $this->assertIsFloat($new->value());

        $this->assertEquals(1, $old->value());
        $this->assertEquals(1.45, $new->value());
    }
}