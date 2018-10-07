<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    protected $status = 200;

    /**
     * @return int
     */
    private function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     * @return $this
     */
    public function setStatus(int $status)
    {
        $this->status = $status;
        return $this;
    }

    public function responseNotFound($message = 'Not Found')
    {
        return $this->setStatus(404)->responseError($message);
    }

    /**
     * @param string $message
     * @return JsonResponse
     */
    public  function responseError(string $message) : JsonResponse
    {
        $status = $this->getStatus();
        $tip = 'failed';
        $errors = compact('tip', 'message');
        return $this->response(compact('status', 'errors'));
    }

    /**
     * 所有类型的返回都可以这样
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function response(array $data): JsonResponse
    {
        return response()->json($data);
    }
}
