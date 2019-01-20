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
        $user = UserModel::field('id, name, password, salt, role, img_url, is_active')
            ->where('name', $name)
            ->find();

        // 判断账户是否激活
        if(!$user->is_active){
            throw new LoginException('账户未激活，请到个人中心激活', ResponseCode::$USER_NOT_ACTIVE);
        }
        // 判断登录的用户是否存在
        if (!$user) {
            throw new LoginException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }

        $salt = $user['salt'];
        $new_password = md5(md5($password).$salt);

        // 判断登录密码是否正确
        if ($user['password'] !== $new_password) {
            throw new LoginException('密码错误', ResponseCode::$PASSWORD_ERROR);
        }
        Session::set('id', $user['id']);
        Session::set('name', $name);
    }

    // 用户退出
    public function logout()
    {
        Session::delete('id');
        Session::delete('name');
    }

    // 获取登录用户信息
    public function getLoginUser($id)
    {
        $id = Session::get('id');
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }
}