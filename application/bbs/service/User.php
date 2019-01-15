<?php
namespace app\bbs\service;

use think\Model;
use think\Facade\Session;
use app\bbs\common\ResultCodeMsg;

class User extends Model
{
    public function login($name, $password)
    {
        $data = $this->where('name', 'eq', $name)->find();
        //  如果查找到数据
        if ($data) {
            //  获取该用户信息对应的盐值
            $salt = $data['salt'];
            // 获取密码的信息摘要与数据库中的密码字符串作对比，看看匹不匹配
            $new_password = md5(md5($password).$salt);
            if ($data['password'] == $new_password) {
                //  获得用户所在ID
                $id = $data['id'];
                //  设置session，用于首页访问，不存在，则返回到登录页面
                Session::set('id', $id);
                Session::set('name', $name);
                return ResultCodeMsg::$SUCCESS;
            } else {
                return ResultCodeMsg::$PASSWORD_ERROR;
            }
        } else {
            return ResultCodeMsg::$USER_DOES_NOT_EXIST;
        }
    }
}