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
use app\bbs\model\UserModel;
use http\Env\Response;
use think\facade\Session;

class UserService
{
    // 用户登录
    public function login($name, $password)
    {
        // 根据用户名称获取登录信息
        $user = UserModel::field('id,name,password,salt')
            ->where('name', 'eq', $name)
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
            throw new LoginException('用户不存在', ResponseCode::$USER_DOES_NOT_EXIST);
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

    // 注册用户
    public function add($name,$password,$email)
    {
        // 判断注册的用户名是否已经存在
        if($this->estimateUserExist($name)){
            throw new RegisterException('此用户名已经存在,请重新设置', ResponseCode::$USERNAME_EXIST);
        }
        // 加密salt和密码
        $salt = $this->getSalt(5);
        $password = md5(md5($password).$salt);

        $user = UserModel::create([
            'name'  =>  $name,
            'password'=> $password,
            'email' =>  $email,
            'salt'=>$salt
        ], ['name', 'password', 'email','salt']);
        if(!$user){
            throw new RegisterException('注册用户出错',ResponseCode::$REGISTER_ERROR);
        }
        return $user;
    }

    // 获取加密的随机salt
    public function getSalt($len)
    {
        $salt = '';
        $str = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";//大小写字母以及数字
        $max = strlen($str)-1;
        for($i=0;$i<$len;$i++){
            $salt.=$str[rand(0,$max)];
        }
        return md5($salt);
    }

    // 判断用户是否存在
    public function estimateUserExist($name){
        if(UserModel::where('name',$name)->find()){
            return true;
        }else{
            return false;
        }
    }
}