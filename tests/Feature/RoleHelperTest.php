<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class RoleHelperTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testExample()
    {
        $rates = [
            ['rate' => 1], // 2
            ['rate' => 2], // 4
            ['rate' => 3], // 6
            ['rate' => 4], // 4
            ['rate' => 5], // 5
            ['rate' => 4], // 4
            ['rate' => 3], // 3
            ['rate' => 2], // 2
            ['rate' => 1], // 1
            ['rate' => 5], // 5
        ];
        $expectedAverage = 3.6;

        $average = calculateRateAverage($rates);
        $this->assertEquals($expectedAverage, $average);
    }
}
