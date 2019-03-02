<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Product;

class ProductTest extends TestCase
{
    private $product;

    public function setUp()
    {
        parent::setUp();
        $this->product = new Product('price', 10000);
    }

    /**
     * @test
     */
    public function AProductHasName()
    {
        $product = new Product('name', 'Hello World');
        $this->assertEquals('Hello World', $product->name());
    }

    public function testAProductHasPrice()
    {
        $this->assertEquals(10000, $this->product->price());
    }

    /**
     * @test
     */
    public function AProductCanBeSoldWithDiscount()
    {
        $this->product->setPrice(8);
        $this->assertEquals(8000, $this->product->price());
    }
}
