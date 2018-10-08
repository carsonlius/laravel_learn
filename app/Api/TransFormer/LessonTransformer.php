<?php

namespace App\Api\TransFormer;

use App\Lesson;
use League\Fractal\TransformerAbstract;

class LessonTransformer extends TransformerAbstract
{
    public function transform(Lesson $lesson) :array
    {
        return [
            "id" => $lesson['id'],
            "title" => $lesson['title'],
            "content" => $lesson["body"],
            "is_free" => !!$lesson['free']
        ];
    }
}