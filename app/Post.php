<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    protected $fillable = ['title', 'body', 'user_id', 'active'];
    protected $dates = ['deleted_at'];

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('post_id', function (Builder $builder) {
            $builder->where('id', '<', 40);
        });
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }
}
