<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    public function scopeTrading($query)
    {
        return $query->orderBy('reads', 'desc')->orderBy('id', 'desc');
    }
}
