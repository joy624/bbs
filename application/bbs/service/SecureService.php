<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/15
 * Time: 下午11:25
 */

namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\LoginException;
use app\bbs\exception\RegisterException;
use app\bbs\exception\UserException;
use app\bbs\model\UserModel;
use think\facade\Session;

class SecureService
{
    // 获取加密的随机salt
    public function genRandomString($len = 32)
    {
        $salt = '';
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";//大小写字母以及数字
        $max = strlen($str)-1;
        for($i=0;$i<$len;$i++){
            $salt.=$str[rand(0,$max)];
        }
        return $salt;
    }

}