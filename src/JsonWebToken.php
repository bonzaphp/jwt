<?php

/**
 * created by bonzaphp@gmail.com
 * @author bonzaphp@gmail.com
 * @date 20190427
 */
class JsonWebToken
{

    //头部json对象
    static private $header = [
        'alg' => 'HS256',
        'typ' => 'JWT',
    ];
    //标准格式
    static private $payload = [
            "iss" => "https://crm.ngrok.bonza.cn",//issuer 签发人
            "aud" => "https://crm.ngrok.bonza.cn",//audience 受众
            "iat" => '',//Issued At 签发时间
            "sub" => 'self sign',//subject 主题
            "nbf" => '',//Not Before 生效时间
            "exp" => '',//过期时间
            "jti" => '',//JWT ID
    ];
    //算出签名以后，把 Header、Payload、Signature 三个部分拼成一个字符串，每个部分之间用"点"（.）分隔，就可以返回给用户
    static private $signature = '';

    function __construct()
    {

    }

    public function encode($value = '')
    {
        return self::getHeader().self::setPayload($value).self::$signature;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param $user_id
     */
    public function setPayload($user_id)
    {
        $payload = [
            "iss" => "https://crm.ngrok.bonza.cn",//issuer 签发人
            "aud" => "https://crm.ngrok.bonza.cn",//audience 受众
            "iat" => time(),//Issued At 签发时间
            "sub" => 'self sign',//subject 主题
            "nbf" => time(),//Not Before 生效时间
            "exp" => time()+3600,//过期时间
            "jti" => 'crm'.date('YmdHis').mt_rand(1000,9999),//JWT ID
            "user_id" => $user_id,//自定义用户id
        ];
        $this->payload = $payload;
    }

    /**
     * @return string
     */
    static public function getHeader():string
    {
        return json_encode(static::$header);
    }


}


