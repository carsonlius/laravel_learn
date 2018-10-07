<?php

namespace App\TransForm;

class LessonTransForm extends TransFormer
{
    /**
     * @param array $lesson
     * @return array
     */
    public static function transForm(array $lesson) : array
    {
        return [
            "id" => $lesson['id'],
            "title" => $lesson['title'],
            "content" => $lesson["body"],
            "is_free" => !!$lesson['free']
        ];
    }
}