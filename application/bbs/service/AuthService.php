<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/18
 * Time: 上午12:36
 */

namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\LoginException;
use app\bbs\model\UserModel;
use think\facade\Session;

class AuthService
{

    // 用户登录
    public function login($name, $password)
    {
        // 根据用户名称获取登录信息
        $user = UserModel::field('id,name,password,salt')
            ->where('name', $name)
            ->find();
        // 判断登录的用户是否存在
        if ($user) {
            $salt = $user['salt'];
            $new_password = md5(md5($password).$salt);
            if ($user['password'] == $new_password) {
                Session::set('id', $user['id']);
                Session::set('name', $name);
            } else {
                throw new LoginException('密码错误', ResponseCode::$PASSWORD_ERROR);
            }
        } else {
            throw new LoginException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }
    }

    // 用户退出
    public function logout()
    {
        Session::delete('id');
        Session::delete('name');
    }

    // 获取登录用户信息
    public function getLoginUser()
    {
        $id = Session::get('id');
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }
}