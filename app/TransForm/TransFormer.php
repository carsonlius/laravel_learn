<?php

namespace App\TransForm;


abstract class TransForm
{
    /**
     * @param array $items
     * @return array
     */
    public static function transForms(array $items): array
    {
        return array_map([new static(), 'transForm'], $items);
    }

    public static abstract function transForm(array $item);

}