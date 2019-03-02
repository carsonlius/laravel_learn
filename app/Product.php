<?php

namespace App;

class Product
{
    private $name;

    public $price;

    /**
     * Product constructor.
     * @param $name
     * @param null $price
     */
    public function __construct($name, $price = null)
    {
        $this->$name= $price ?? $name;
    }

    public function name()
    {
        return $this->name;
    }

    public function price()
    {
        return $this->price;
    }

    public function setPrice($discount)
    {
        $this->price *= $discount/10;
    }
}
