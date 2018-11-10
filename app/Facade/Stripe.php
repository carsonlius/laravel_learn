<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class Stripe extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'billing';
    }
}