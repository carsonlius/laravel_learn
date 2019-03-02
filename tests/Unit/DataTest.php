<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DataTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     * @param $a
     * @param $b
     * @param $expected
     */
    public function testAdd($a, $b, $expected)
    {
        $this->assertEquals($expected, $a+$b);
    }

    public function additionProvider()
    {
        return [
            'first'  => [0, 0, 0],
            'second' => [0, 1, 1],
            'third' => [1, 0, 1],
            'fourth'  => [1, 1, 3]
        ];
    }
}
