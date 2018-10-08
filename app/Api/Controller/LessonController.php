<?php

namespace App\Api\Controller;

use App\Api\Repository\LessonRepository;
use App\Api\TransFormer\LessonTransformer;
use App\Lesson;

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
            return $this->response->collection(collect($list_lessons), new LessonTransformer);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 21312);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            $this->repository_lesson->store();
            $status = 0;
            $msg = '开通课程成功';
            return $this->response(compact('status', 'msg'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * @param Lesson $lesson
     * @return \Dingo\Api\Http\Response
     */
    public function show($lesson)
    {
        try {
            $lesson = Lesson::find($lesson);

            if (!$lesson) {
                return $this->response->errorNotFound();
            }

            return $this->response->item($lesson, new LessonTransformer);
        } catch (\Exception $e) {
            $this->response->error($e->getMessage(), 503);
        }

    }

}