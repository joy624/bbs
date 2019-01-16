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
use think\db\Where;
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
        return UserModel::field('id')->where('name',$name)->find();
    }

    // 验证指定用户的密码是否正确
    public function verifyPassword($id,$old_password)
    {
        // 获取指定用户的password和salt
        $data = UserModel::field('password, salt')->where('id',$id)->find();
       if($data->password !== md5(md5($old_password).$data->salt)) {
           throw new RegisterException('密码输入错误',ResponseCode::$PASSWORD_ERROR);
       }
    }

    // 重置用户密码
    public function resetPassword($id, $new_password)
    {
        $salt = $this->getSalt(5);
        $password = md5(md5($new_password).$salt);
        $user = new UserModel;
        $res = $user->save([
            'salt'  => $salt,
            'password' => $password
        ],['id' => $id]);
        if(!$res){
            throw new RegisterException('重置密码失败',ResponseCode::$RESETPASSWORD_ERROR);
        }
        return $res;
    }

    // 保存激活码和有效期
    public function addToken($id,$token,$token_exptime)
    {
        $user = new UserModel;
        $res = $user->save([
            'token'  => $token,
            'token_exptime' => $token_exptime
        ],['id' => $id]);
        if(!$res){
            throw new RegisterException('保存激活码和激活码有效性失败',ResponseCode::$TOKEN_ERROR);
        }
        return $res;
    }

    // 激活用户
    public function validateToken($token)
    {
        $data = UserModel::field('token_exptime,is_active')->where('token',$token)->find();

        // 检测用户是否存在
        if(!$data){
            throw new RegisterException('用户不存在',ResponseCode::$USER_NOT_EXIST);
        }
        // 检测激活码是否过期
        if((time()-$data->token_exptime)>24*60*60){
            throw new RegisterException('激活码过期',ResponseCode::$TOKEN_EXPTIME);
        }
        // 检测用户是否激活
        if($data->is_active){
            throw new RegisterException('用户已激活',ResponseCode::$USER_ACTIVATED);
        }
        $user = new UserModel;
        $res = $user->save([
            'is_active'=> 1
        ],['token' => $token]);
        if(!$res){
            throw new RegisterException('激活用户失败',ResponseCode::$USER_ACTIVATED_ERROR);
        }
        return $res;
    }


}