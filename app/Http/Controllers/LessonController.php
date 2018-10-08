<?php

namespace App\Http\Controllers;

use App\Http\Repository\LessonRepository;
use App\Lesson;
use Illuminate\Http\Request;

class LessonController extends ApiController
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $list_lessons = $this->repository_lesson->index();
            $status = 0;
            return $this->response(compact('status', 'list_lessons'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Display the specified resource.
     *
     * @param  \App\Lesson $lesson
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Lesson $lesson)
    {
        try {
            $status = 0;
            $lesson = $this->repository_lesson->transForm($lesson->toArray());
            return $this->response(compact('status', 'lesson'));
        } catch (\Exception $e) {
            return $this->setStatus(1478)->responseError($e->getMessage());
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function edit(Lesson $lesson)
    {
        return auth()->payload();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Lesson $lesson)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Lesson $lesson
     * @return \Illuminate\Http\Response
     */
    public function destroy(Lesson $lesson)
    {
        //
    }
}
