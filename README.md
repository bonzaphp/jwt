# php-jwt

- - - 

## 安装

```
composer require bonza/jwt
```

## 使用
1. 首先引入具体实现`use bonza\jwt\JWTHash256;`
2. 配置必要的参数
```
$header = [
    'alg' => 'sha256',//算法
    'typ' => 'JWT',
];
$payload = [
    "iss" => "https://example.cn",//issuer 签发人
    "aud" => "https://example.cn",//audience 受众
    "iat" => time(),//Issued At 签发时间
    "sub" => 'self sign',//subject 主题
    "nbf" => time(),//Not Before 生效时间
    "exp" => time()+3600,//过期时间
    "jti" => 'exp'.date('YmdHis').random_int(1000,9999),//JWT ID
];
```
3. 应用
```
$key = 'jwt-key';

$token = JWTHash256::encode($header,$payload,$key);
```

### json web token

暂时只实现了sha256签名认证
