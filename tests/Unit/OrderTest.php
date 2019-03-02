<?php

namespace Tests\Unit;

use App\{
    Product, Order
};
use PHPUnit\Framework\TestCase;

class OrderTest extends TestCase
{
    private $product;
    private $product2;
    private $order;

    public function setUp()
    {
        parent::setUp();
        $this->order = new Order();
        $this->product = new Product('price', 1000);
        $this->product2 = new Product('price', 2000);
        $this->order->add($this->product);
        $this->order->add($this->product2);
    }

    /**
     * @test
     */
    public function AnOrderConsistsProducts()
    {


//        $this->assertEquals(2, count($order->products()));
        $this->assertCount(2, $this->order->products(), '与期望不符合');
    }

    /**
     * @test
     */
    public function weCanGetTotalCostFromAllProductOfAnOrder()
    {
        $this->assertEquals(3000, $this->order->total());
    }
}
