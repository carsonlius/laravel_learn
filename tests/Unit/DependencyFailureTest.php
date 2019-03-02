<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DependencyFailureTest extends TestCase
{
    public function testOne()
    {
        $this->assertEmpty([]);
    }

    /**
     * @depends testOne
     */
    public function testTwo()
    {
        $this->assertEquals('foo', 'foo');
    }
}
