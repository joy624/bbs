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
use app\bbs\model\UserModel;
use think\facade\Session;

class UserService
{

    public function login($name, $password)
    {

        $user = UserModel::field('id,name,password,salt')
            ->where('name', 'eq', $name)
            ->find();
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
            throw new LoginException('用户不存在', ResponseCode::$USER_DOES_NOT_EXIST);
        }
    }

    public function logout()
    {
        Session::delete('id');
        Session::delete('name');
    }

    public function getLoginUser()
    {
        $id = Session::get('id');
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }
}