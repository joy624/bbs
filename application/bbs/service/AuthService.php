<?php
namespace app\bbs\service;

use app\bbs\model\UserModel;
use think\facade\Session;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

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
            throw new UserException('账户未激活，请到个人中心激活', ResponseCode::$USER_NOT_ACTIVE);
        }
        // 判断登录的用户是否存在
        if (!$user) {
            throw new UserException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }

        $salt = $user['salt'];
        $register_password = md5(md5($password).$salt);

        // 判断登录密码是否正确
        if ($user['password'] !== $register_password) {
            throw new UserException('用户密码错误', ResponseCode::$USER_PASSWORD_ERROR);
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
   public function getLoginUser()
    {
        // 判断当前用户是否已登录
        $id = Session::get('id');
        if (!$id) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }
}