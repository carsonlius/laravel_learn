<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable=['title', 'body', 'free'];
//    protected $hidden = ['free'];
}