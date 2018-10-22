<?php

class Jwt
{
    private $des;
    private function __construct(string $des)
    {
        $this->des = $des;
    }

    /**
     * hash解密
     * @param string $des
     * @param string $str_encode
     */
    public static function baseDecode(string $des, string $str_encode)
    {
        (new static($des))->baseDecodeDo($str_encode);
    }

    /**
     * 生成签名
     * @param string $header
     * @param string $payload
     */
    public static function genSign(string $des, string $header, string $payload)
    {

        return (new static($des))->genSignDo($header, $payload);
    }

    private function genSignDo(string $header, string $payload)
    {
        global $sign;
        global $key;
        $sign_hash = $this->signature($header . '.' . $payload, $key, 'HS256');
        echo $this->des . $sign_hash . PHP_EOL;

        echo  $sign === $sign_hash ? ' 完全一致，验证通过 '  : '不一致 ';
        echo ''. PHP_EOL;
    }

    protected function baseDecodeDo(string $str_encode)
    {
        echo $this->des . base64_decode($str_encode) . PHP_EOL;
    }

    /**
     * base64UrlEncode   https://jwt.io/  中base64UrlEncode编码实现
     * @param string $input 需要编码的字符串
     * @return string
     */
    private function base64UrlEncode(string $input)
    {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * base64UrlEncode  https://jwt.io/  中base64UrlEncode解码实现
     * @param string $input 需要解码的字符串
     * @return bool|string
     */
    private static function base64UrlDecode(string $input)
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $addlen = 4 - $remainder;
            $input .= str_repeat('=', $addlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * HMACSHA256签名   https://jwt.io/  中HMACSHA256签名实现
     * @param string $input 为base64UrlEncode(header).".".base64UrlEncode(payload)
     * @param string $key
     * @param string $alg   算法方式
     * @return mixed
     */
    private  function signature(string $input, string $key, string $alg = 'HS256')
    {
        $alg_config=array(
            'HS256'=>'sha256'
        );
        return $this->base64UrlEncode(hash_hmac($alg_config[$alg], $input, $key,true));
    }
}

$header = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9';
Jwt::baseDecode('header 解码之后数据: ', $header);
$payload = 'eyJpc3MiOiJodHRwOlwvXC9sZWFybi5jYXJzb25saXVzLnZpcFwvYXBpXC9hdXRoXC9sb2dpbiIsImlhdCI6MTUzOTI1NzI4MywiZXhwIjoxNTM5MjYwODgzLCJuYmYiOjE1MzkyNTcyODMsImp0aSI6Ik8xcmtySE94OXluUDRRTUwiLCJzdWIiOjEsInBydiI6Ijg3ZTBhZjFlZjlmZDE1ODEyZmRlYzk3MTUzYTE0ZTBiMDQ3NTQ2YWEifQ';
Jwt::baseDecode('payload解码之后的数据: ', $payload);
$sign = 'EI_tKkVoq0uYDmZmw2-4riA7sWjw02jvivxiWUwidP8';
$key = 'wf0BAefvZB5S2efYmgEnMItMa9tzpnrv';

Jwt::genSign('签名字符串 : ', $header, $payload);