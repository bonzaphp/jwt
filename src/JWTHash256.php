<?php

/**
 * created by bonzaphp@gmail.com
 * @author bonzaphp@gmail.com
 * @date 20190427
 */

namespace bonza\jwt;


use Exception;
use UnexpectedValueException;

class JWTHash256 extends JWTBase
{

    //头部json对象
    private static $header = [
        'alg' => 'sha256',//算法
        'typ' => 'JWT',
    ];
    //标准格式
    private static $payload = [];

    private function __construct()
    {
        //禁止实例化
    }

    /**
     * 算出签名以后，把 Header、Payload、Signature 三个部分拼成一个字符串，每个部分之间用"点"（.）分隔，就可以返回给用户
     * @param array $header
     * @param array $payload
     * @param $key
     * @return string
     */
    public static function encode(array $header, array $payload, $key): string
    {
        self::$header = !empty($header) ? $header : static::$header;
        static::$payload = $payload;
        return static::getHeader() . '.' . static::getPayload() . '.' . static::sign(static::getHeader() . static::getPayload(), $key);
    }

    /**
     * @return string
     */
    public static function getPayload(): string
    {
        return static::base64url_encode(json_encode(static::$payload));
    }

    /**
     * 设置负载
     * @param array $payload
     */
    public static function setPayload(array $payload): void
    {
        static::$payload = $payload;
    }

    /**
     * @return string
     */
    public static function getHeader(): string
    {
        return static::base64url_encode(json_encode(static::$header));
    }


    /**
     * 解码jwt
     * @param string $jwt_token
     * @param string $key
     * @return object|\stdClass
     * @throws Exception
     */
    public static function decode(string $jwt_token, string $key): object
    {
        $tks = explode('.', $jwt_token);
        if (count($tks) !== 3) {
            throw new UnexpectedValueException('非法token');
        }
        [$headb64, $bodyb64, $cryptob64] = $tks;
        if (null === ($header = static::jsonDecode(static::base64url_decode($headb64)))) {
            throw new UnexpectedValueException('无效头信息');
        }
        if (null === $payload = static::jsonDecode(static::base64url_decode($bodyb64))) {
            throw new UnexpectedValueException('无效负载');
        }
        if (false === ($sign = static::base64url_decode($cryptob64))) {
            throw new UnexpectedValueException('无效签名');
        }
        if (empty($header->alg)) {
            throw new UnexpectedValueException('没有指定加密方式');
        }
        // Check the signature
        if (!static::verify($headb64 . $bodyb64, $key, $sign)) {
            throw new Exception('签名校验失败');
        }
        return $payload;
    }

    /**
     * 生成签名
     * @param string $msg
     * @param string $key
     * @return string
     */
    private static function sign(string $msg, string $key): string
    {
        return static::base64url_encode(hash_hmac('sha256', $msg, $key, true));
    }

    /**
     * 验证签名
     * @param string $msg
     * @param string $key
     * @param string $signature
     * @return bool
     */
    private static function verify(string $msg, string $key, string $signature): bool
    {
        $hash = hash_hmac('sha256', $msg, $key, true);
        if (function_exists('hash_equals')) {
            return hash_equals($signature, $hash);
        }
        $len = min(static::safeStrlen($signature), static::safeStrlen($hash));
        $status = 0;
        for ($i = 0; $i < $len; $i++) {
            $status |= (ord($signature[$i]) ^ ord($hash[$i]));
        }
        $status |= (static::safeStrlen($signature) ^ static::safeStrlen($hash));
        return ($status === 0);
    }


}


