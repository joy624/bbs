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

class UserService
{
    // 注册用户
    public function addUser($name, $password, $email)
    {
        // 判断注册的用户名是否已经存在
        if($this->getUserByName($name)){
            throw new RegisterException('此用户名已经存在,请重新设置', ResponseCode::$USERNAME_EXIST);
        }
        if($this->getUserByEmail($email)){
            throw new RegisterException('此邮箱已经存在,请重新设置', ResponseCode::$EMAIL_EXIST);
        }

        // 加密salt和密码
        $secure_service = new SecureService();
        $salt = $secure_service->genRandomString();
        $password = md5(md5($password) . $salt);

        $user = new UserModel();
        if(!$user->save(['name'  =>  $name, 'password'=> $password, 'email' =>  $email, 'salt'=>$salt])){
            throw new RegisterException('注册用户出错',ResponseCode::$REGISTER_ERROR);
        }

        return UserModel::field(UserModel::getSafeAttrs())->get($user->id);
    }


    public function editActiveFlag($id,$flag)
    {
        $user = new UserModel();
        if(!$user->save(['is_active'  => $flag,], ['id' => $id])){
            throw new UserException('激活账户失败',ResponseCode::$ACTIVATE_FLAG_ERROR);
        }
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }
    public function getUserByName($name){
        return UserModel::field(UserModel::getSafeAttrs())->where('name',$name)->find();
    }

    public function getUserByEmail($email){
        return UserModel::field(UserModel::getSafeAttrs())->where('email',$email)->find();
    }

    // 验证指定用户的密码是否正确
    public function verifyPassword($id, $old_password)
    {
        $user = UserModel::field('password, salt')->where('id',$id)->find();
        if (!$user) {
            throw new UserException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }
        if($user->password !== md5(md5($old_password) . $user->salt)) {
            throw new RegisterException('密码输入错误',ResponseCode::$PASSWORD_ERROR);
        }
    }

    // 重置用户密码
    public function updatePassword($id, $new_password)
    {
        $secure_service = new SecureService();
        $salt = $secure_service->genRandomString();
        $password = md5(md5($new_password) . $salt);
        $user = new UserModel;
        $res = $user->save([
            'salt'  => $salt,
            'password' => $password
        ], ['id' => $id]);
        if(!$res){
            throw new RegisterException('重置密码失败',ResponseCode::$RESETPASSWORD_ERROR);
        }
    }

//    // 保存激活码和有效期
//    public function addToken($id,$token,$token_exptime)
//    {
//        $user = new UserModel;
//        $res = $user->save([
//            'token'  => $token,
//            'token_exptime' => $token_exptime
//        ], ['id' => $id]);
//        if(!$res){
//            throw new RegisterException('保存激活码和激活码有效性失败',ResponseCode::$TOKEN_ERROR);
//        }
//        return $res;
//    }

//    // 激活用户
//    public function validateToken($token)
//    {
//        $data = UserModel::field('token_exptime,is_active')->where('token',$token)->find();
//
//        // 检测用户是否存在
//        if(!$data){
//            throw new RegisterException('用户不存在',ResponseCode::$USER_NOT_EXIST);
//        }
//        // 检测激活码是否过期
//        if((time()-$data->token_exptime)>24*60*60){
//            throw new RegisterException('激活码过期',ResponseCode::$TOKEN_EXPTIME);
//        }
//        // 检测用户是否激活
//        if($data->is_active){
//            throw new RegisterException('用户已激活',ResponseCode::$USER_ACTIVATED);
//        }
//        $user = new UserModel;
//        $res = $user->save([
//            'is_active'=> 1
//        ],['token' => $token]);
//        if(!$res){
//            throw new RegisterException('激活用户失败',ResponseCode::$USER_ACTIVATED_ERROR);
//        }
//        return $res;
//    }

    public function saveThumb($id, $thumb_path)
    {
        if(!UserModel::get($id)){
            throw new RegisterException('用户不存在',ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel();
        $res = $user->save([
            'img_url'  => $thumb_path
        ], ['id' => $id]);
        if(!$res){
            throw new RegisterException('保存头像路径错误',ResponseCode::$THUMB_URL_SAVE_ERROR);
        }
    }


    public function modifyEmail($id, $email)
    {
        if(UserModel::whereEmail('=',$email)->find()){
            throw new RegisterException('已存在，请重新设置',ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel;
        $res = $user->save([
            'email'  => $email,
            'is_active'=>0
        ], ['id' => $id]);
        if(!$res){
            throw new RegisterException('修改错误',ResponseCode::$EDIT_ERROR);
        }
    }

    public function modifyName($id, $name)
    {
        if(!UserModel::get($id)){
            throw new RegisterException('用户不存在',ResponseCode::$USER_NOT_EXIST);
        }
        if(UserModel::whereName('=',$name)->find()){
            throw new RegisterException('已存在，请重新设置',ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel;
        if(! $user->save(['name'  => $name], ['id' => $id])){
            throw new RegisterException('修改错误',ResponseCode::$EDIT_ERROR);
        }
    }
}