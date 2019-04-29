<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/4/29
 * Time: 9:06
 */

interface JWT
{
    /**
     * 编码jwt_token
     * @param array $header
     * @param array $payload
     * @param string $key //签名密钥
     * @return string
     */
    static public function encode(array $header,array $payload,string $key): string;

    /**
     * 解码jwt_token
     * @param string $jwt_token //jwt_token字符串
     * @param string $key //签名密钥
     * @return object
     */
    static public function decode(string $jwt_token, string $key): object;
}