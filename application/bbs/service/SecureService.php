<?php
namespace app\bbs\service;

class SecureService
{
    // 获取加密的随机salt,默认返回32位随机字符串
    public function genRandomString($len = 32)
    {
        $salt = '';
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($str)-1;
        for($i=0;$i<$len;$i++){
            $salt.=$str[rand(0,$max)];
        }
        return $salt;
    }

}