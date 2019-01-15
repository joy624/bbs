<?php

namespace app\test\model;

use think\Model;
use think\Facade\Session;
use app\test\controller\ResultCode;

class User extends Model
{
    /**
     * 登录操作.
     *
     * @param string $name
     * @param string $pwd
     *
     * @return int
     */
    public function login($name, $pwd)
    {
        $userData = User::where('name', 'eq', $name)->find();
        //  如果查找到数据
        if ($userData) {
            //  获取该用户信息对应的盐值
            $salt = $userData['salt'];
            // 获取密码的信息摘要与数据库中的密码字符串作对比，看看匹不匹配
            $newPwd = md5(md5($pwd).$salt);
            if ($userData['password'] == $newPwd) {
                //  获得用户所在ID
                $id = $userData['id'];
                //  设置session，用于首页访问，不存在，则返回到登录页面
                Session::set('id', $id);
                Session::set('name', $name);

                return ResultCode::$LOGIN_SUCCESS;
            } else {
                return ResultCode::$PASSWORD_ERROR;
            }
        } else {
            return ResultCode::$USER_DOES_NOT_EXIST;
        }
    }
}
