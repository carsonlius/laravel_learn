<?php

namespace App\Api\Repository;

use App\Lesson;
use App\TransForm\LessonTransForm;

class LessonRepository
{
    /**
     * @throws \Exception
     */
    public function store()
    {
        // 检查条件
        $this->verifyParamsForStore();

        // 生成条件
        $params = $this->genParamsForStore();

        // create
        $this->storeDo($params);
    }

    /**
     * create
     * @param array $params
     */
    protected function storeDo(array $params)
    {
        Lesson::create($params);
    }

    protected function genParamsForStore() : array
    {
        $list_params = request()->only('title', 'body', 'free');
        return array_map(function($item){
            return trim($item);
        }, $list_params);
    }

    /**
     * @throws \Exception
     */
    protected function verifyParamsForStore()
    {
        if (!request()->has('title') || !trim(request()->post('title'))) {
            throw new \Exception('缺少必须参数title');
        }

        if (!request()->has('body') || !trim(request()->post('body'))) {
            throw new \Exception('缺少必须参数body');
        }
        if (!request()->has('free')) {
            throw new \Exception('缺少必须参数free');
        }
    }


    /**
     * @return array
     */
    public function index()
    {
        return Lesson::latest()->get();
    }

    /**
     * @param array $lesson
     * @return array
     */
    public function transForm(array $lesson): array
    {
        return LessonTransForm::transForm($lesson);
    }
}