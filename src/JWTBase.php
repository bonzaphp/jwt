<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/4/29
 * Time: 10:06
 */
namespace bonza\jwt;


abstract class JWTBase implements JWT
{

    /**
     * 编码jwt_token
     * @param array $header
     * @param array $payload
     * @param string $key //签名密钥
     * @return string
     */
    abstract static public function encode(array $header,array $payload,string $key): string;

    /**
     * 解码jwt_token
     * @param string $jwt_token
     * @param string $key //签名密钥
     * @return object
     */
    abstract static public function decode(string $jwt_token, string $key): object ;

    /**
     * base64 url编码
     * @param $input
     * @return string
     */
    protected static function base64url_encode(string $input):string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }

    /**
     * base64解码URL传过来的数据
     * @param $input
     * @return string
     */
    protected static function base64url_decode(string $input):string
    {
        return base64_decode(strtr($input, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($input)) % 4));
    }

    /**
     * json编码
     * @param $input
     * @return string
     */
    protected static function jsonEncode($input)
    {
        $json = json_encode($input);
        if (function_exists('json_last_error') && $errMsg = json_last_error()) {
            static::handleJsonError($errMsg);
        } elseif ($json === 'null' && $input !== null) {
            throw new \DomainException('编码错误');
        }
        return $json;
    }

    /**
     * 处理json编码过程中可能出现的异常
     * @param $errMsg
     */
    protected static function handleJsonError($errMsg)
    {
        $messages = [
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_STATE_MISMATCH => 'Underflow or the modes mismatch',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON',
            JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded', //PHP >= 5.3.3
            JSON_ERROR_NONE => 'Unknown error',
        ];
        throw new \DomainException(
            isset($messages[$errMsg])
                ? $messages[$errMsg]
                : 'Unknown JSON error: ' . $errMsg
        );
    }

    /**
     * 解码jwt
     * @param $input
     * @return mixed
     */
    protected static function jsonDecode($input)
    {
        if (version_compare(PHP_VERSION, '5.4.0', '>=') && !(defined('JSON_C_VERSION') && PHP_INT_SIZE > 4)) {
            $obj = json_decode($input, false, 512, JSON_BIGINT_AS_STRING);
        } else {
            $max_int_length = strlen((string) PHP_INT_MAX) - 1;
            $json_without_bigints = preg_replace('/:\s*(-?\d{'.$max_int_length.',})/', ': "$1"', $input);
            $obj = json_decode($json_without_bigints);
        }

        if (function_exists('json_last_error') && $errMsg = json_last_error()) {
            static::handleJsonError($errMsg);
        } elseif ($obj === null && $input !== 'null') {
            throw new \DomainException('Null result with non-null input');
        }
        return $obj;
    }

    /**
     * 安全获取字符串长度
     *
     * @param string
     *
     * @return int
     */
    protected static function safeStrlen($str)
    {
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, '8bit');
        }
        return strlen($str);
    }
}