<?php

$authorization = 'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9sZWFybi5jYXJzb25saXVzLnZpcFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTUzODg4MDk2NSwiZXhwIjoxNTM4ODg0NTY1LCJuYmYiOjE1Mzg4ODA5NjUsImp0aSI6Im10Rm1YQUMyQldjR2FhTVIiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ.xZGnrbo8fDGQ8OstGhaDlsEaPP-00sHumwUpsrA-zdw';
$url = 'http://learn.carsonlius.vip/api/lesson/1';
var_dump(curlAuth::curlAuth($url, $authorization));

class curlAuth
{
    private $authorization = [];
    private $url;

    /**
     * curlAuth constructor.
     * @param string $url
     * @param string $authorization
     */
    private function __construct(string $url, string $authorization)
    {
        $this->url = $url;
        array_push($this->authorization, $authorization);
    }

    /**
     * @param string $url
     * @param string $authorization
     * @return array
     */
    public static function curlAuth(string $url, string $authorization): array
    {
        return (new static($url, $authorization))->curlRequest();
    }

    /**
     * @return array
     */
    private function curlRequest(): array
    {
        //初始化
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->authorization);
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $this->url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, false);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);

        if (is_string($data)) {
            return json_decode($data, true);
        }

        //显示获得的数据
        return $data;
    }
}

