<?php

namespace App\Api\Controller;

use App\Api\Repository\LessonRepository;
use App\Api\TransFormer\LessonTransformer;

class LessonController extends BaseController
{
    protected $repository_lesson;

    /**
     * LessonController constructor.
     * @param $repository_lesson
     */
    public function __construct(LessonRepository $repository_lesson)
    {
        $this->repository_lesson = $repository_lesson;
    }

    public function index()
    {
        try {
            $list_lessons = $this->repository_lesson->index();
            throw new \Exception('wa ha ha ');
            return $this->response->collection(collect($list_lessons), new LessonTransformer);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 21312);
        }
    }

}