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
     * @param string $key //签名密钥
     * @return string
     */
    public function encode(string $key): string;

    /**
     * 解码jwt_token
     * @param string $jwt_token //jwt_token字符串
     * @param string $key //签名密钥
     * @return object
     */
    public function decode(string $jwt_token, string $key): object;
}