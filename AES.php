<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/1
 * Time: 1:50
 */

//注意格式一定要有下面的标识符
$pub_key = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCjwmQtK4aYLSL/aOSH4g4fdTBT1JLzeRchbR6fMylOvTjGMh4IngxCwi7NAbTm8Edr02s7HXmo7oweLfqDRHvYPz7aH5Kt6gtjGzokfIVo6nN+3jDfoNBws+pPDaro5KbeIVO0kK16m+51yPS4R3lFF6bZcrGb+xq8A/QrPHxWNQIDAQAB
-----END PUBLIC KEY-----";

$pri_key = "-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQCjwmQtK4aYLSL/aOSH4g4fdTBT1JLzeRchbR6fMylOvTjGMh4IngxCwi7NAbTm8Edr02s7HXmo7oweLfqDRHvYPz7aH5Kt6gtjGzokfIVo6nN+3jDfoNBws+pPDaro5KbeIVO0kK16m+51yPS4R3lFF6bZcrGb+xq8A/QrPHxWNQIDAQABAoGAemICn8dRlVTWPO7VK8ADMftQnLXXBOJQKQj1w6BmlJPRZD18OJB1NUcN1uQZoCWeGrUsBEfo7hko2j0eZQ+/RRCanxM34l0cHP4WQLVYzZ5jGcAyFl3f6Ra4MKELGD3daynfct8z5+XsnD7Fwg/kWNWZ8NJIKHICoqBrgF6wnJkCQQDZUBhAarJsMdebDPw/wj8ovTYZWJf0oqoL+FjDsJF4p8e0MDMIGmsBTPbv5wVaF+8/EFTQ7PDhD0oWRKBpeT6/AkEAwOmgUPXWmxi/TtwjFfX7290GXERjkCwGc5Yj8bVh4YdjPl2ijaFFrogvr3gCKFDd9AD/Oz5zKcrxSl4H5sZcCwJBAMiUuD3E/fkFrFduDeqf1YI52xRcBK4F8mToDq5ZbHxsiNUVZBUHpVrm+kqG9xaoXujbnx3UhaWGYkDZiSKxiasCQQCD7MEX3KcgdbIOqfjMgeX1G5fH7XTxGUpoLVrzZwlDBCVYdww9MvbGPpfttXI0Q+klfrEMwM5c3E5afyeEKE61AkA0m6sjb5ypwXMbXo5+uSEHkpL0Qqb87SCRVV/Bli7OJNuv9DrdwVO27AA192WxUoDfC23faeeETB1Su4M785U6
-----END RSA PRIVATE KEY-----";

$str = "这个是要加密的字符串,这个函数可用来判断公钥是否是可用的";

//这个例子是演示RSA加密
//非对称加密 分为 公钥和私钥
//通常 公钥加密 私钥解密，私钥加密，公钥用来验签

/*
 * 用到的加密函数
openssl_private_decrypt-使用私钥解密数据
openssl_private_encrypt —使用私钥加密数据
openssl_public_decrypt —使用公共密钥解密数据
openssl_public_encrypt —使用公共密钥加密数据
 *
 */
echo "加密前:".$str;
echo "<br/>";
//echo $private_key;
$pi_key =  openssl_pkey_get_private($pri_key);//这个函数可用来判断私钥是否是可用的，可用返回资源id Resource id
$pu_key = openssl_pkey_get_public($pub_key);//这个函数可用来判断公钥是否是可用的
print_r($pi_key);echo "\n";
echo "<br/>";
print_r($pu_key);echo "\n";
echo "<br/>";


//公钥加密过程
$enStr = '';
openssl_public_encrypt($str,$enStr,$pu_key); //参数的意义: 密文数据, 加密解密后的数据,密钥,加密解密的填充(没填)

//加密后的字符 有些无法显示 需要 base64_encode ( string $data ) ,解密的时候不需要要 base64_decode ( string $data ) 
echo "签名后:".base64_encode ($enStr); 
echo "<br/>";

//私钥解密过程 
$deStr = ''; 
//$enStr = base64_decode ( $enStr); 
openssl_private_decrypt($enStr,$deStr,$pi_key); 
echo '解密后:'.$deStr; 
echo "<br/>"; 


//下面是签名验证例子 
$binary_signature = ""; 
//至少使用PHP 5.2.2 / OpenSSL 0.9.8b（Fedora 7）
//似乎无需调用openssl_get_privatekey或类似名称。
//只需传递上面定义的密钥
openssl_sign($str, $binary_signature, $pi_key, OPENSSL_ALGO_SHA1); 
//检查签名
$ok = openssl_verify($str, $binary_signature, $pu_key, OPENSSL_ALGO_SHA1); 
echo "check #1: "; 
if ($ok == 1) 
{ 
    echo "签名正常（应该是）\n"; 
} 
elseif ($ok == 0) 
{ 
    echo "不好（出了点问题）\n"; 
} 
else 
{ 
    echo "丑陋，错误检查签名\n"; 
}