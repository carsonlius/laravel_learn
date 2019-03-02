<?php

namespace App;

class Order
{
    private $list_product = [];

    public function add(Product $product)
    {
        array_push($this->list_product, $product);
    }

    public function products()
    {
        return $this->list_product;
    }

    public function total()
    {
        return array_reduce($this->list_product, function($carry, $item){
            $carry += $item->price;
            return $carry;
        }, 0);
    }
}