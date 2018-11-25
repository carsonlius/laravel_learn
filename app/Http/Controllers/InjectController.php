<?php

namespace App\Http\Controllers;

use App\Http\Repository\LessonRepository;

class InjectController extends Controller
{
    private $repository;

    /**
     * InjectController constructor.
     * @param $repository
     */
    public function __construct(LessonRepository $repository)
    {
        $this->repository = $repository;
    }

    public function sayHello()
    {
        try {
            $this->repository->sayHello();
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            $status = 1478;
            return response()->json(compact('status', 'msg'));
        }
    }
}
