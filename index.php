<?php
/**
 * Created by yang
 * User: bonzaphp@gmail.com
 * Date: 2019/4/29
 * Time: 11:51
 */


$header = [
    'alg' => 'sha256',//算法
    'typ' => 'JWT',
];
$payload = [
    "iss" => "https://crm.ngrok.bonza.cn",//issuer 签发人
    "aud" => "https://crm.ngrok.bonza.cn",//audience 受众
    "iat" => time(),//Issued At 签发时间
    "sub" => 'self sign',//subject 主题
    "nbf" => time(),//Not Before 生效时间
    "exp" => time()+3600,//过期时间
    "jti" => 'crm'.date('YmdHis').random_int(1000,9999),//JWT ID
];



require './src/JWT.php';

require './src/JWTBase.php';

require './src/JWTHash256.php';

/*$jwt = new JWTHash256([],$payload);
$key = 'jwt-key';

$token = $jwt->encode($key);
try {
    echo json_encode($jwt->decode($token, $key));
} catch (\Exception $e) {
    echo $e->getMessage();
}*/
$key = 'jwt-key';

$token = JWTHash256::encode($header,$payload,$key);
try {
    echo json_encode(JWTHash256::decode($token, $key));
} catch (\Exception $e) {
    echo $e->getMessage();
}