<?php

namespace app\bbs\controller;

use think\Controller;
use think\facade\Cache;
use app\bbs\common\Constants;
use app\bbs\service\AuthService;
use app\bbs\service\RegisterService;
use app\bbs\service\UserService;
use app\bbs\service\UploadService;
use app\bbs\validate\UserValidate;
use app\bbs\validate\ResetPasswordValidate;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class UserController extends Controller
{
    // 用户注册
    public function register()
    {
        $params['name'] = $this->request->post('name');
        $params['password'] = $this->request->post('password');
        $params['email'] = $this->request->post('email');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('register')->check($params)) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        $register_service = new RegisterService();
        $user = $register_service->register($params['name'], $params['password'], $params['email']);

        return ResponseCode::success($user);
    }

    // 激活账户
    public function activateAccount()
    {
        $key = $this->request->get('key');
        $id = Cache::get('activate_email_url_' . $key);
        if (!$id) {
            throw new UserException('激活链接已过期或非法输入，请重新激活账户', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
        }
        $user_service = new UserService();
        $user_service->editActiveFlag($id, Constants::USER_ACTIVE);

        $msg ='用户账号激活成功，请返回网站继续进行操作';
        $this->assign('msg',$msg);
        return $this->fetch("tips");
    }

    // 忘记密码，发送邮件找回密码
    public function findPassword()
    {
        $email = $this->request->post('email');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('editEmail')->check(['email' => $email])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 根据邮箱获取用户名
        $user_service = new UserService();
        $user_service->sendEmailForFindingPass($email);
        return ResponseCode::success(true);
    }

    // 根据邮件链接更新密码
    public function updatePwd()
    {
        // get请求，获取邮箱码，重置密码；post请求，重置用户密码
        if ($this->request->isPost()) {
            $key = $this->request->get('key');
            $password = $this->request->post('password');

            $id = Cache::get('validate_email_url_' . $key);
            if (!$id) {
//                throw new UserException('验证信息已过期或非法输入，请重新找回密码', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
                $msg ='验证信息已过期或非法输入，请重新激活账户';
                $this->assign('msg',$msg);
                return $this->fetch('tips');
            }

            // 利用UserValidate验证器验证密码是否符合指定的规范
            $validate = new UserValidate();
            if (!$validate->scene('updatePwd')->check(['password' => $password])) {
                throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
            }

            $user_service = new UserService();
            $user_service->updatePassword($id, $password);
            Cache::set('validate_email_url_' . $key,null);
//            return ResponseCode::success(true);
            $this->success("找回密码成功","http://localhost:8080/login");
        } else {
            $key = $this->request->get('key');
            $id = Cache::get('validate_email_url_' . $key);
            if (!$id) {
//                throw new UserException('验证信息已过期或非法输入，请重新找回密码', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
                $msg ='验证信息已过期或非法输入，请重新激活账户';
                $this->assign('msg',$msg);
                return $this->fetch('tips');
            }
           // return ResponseCode::success($key);//todo html
            return $this->fetch();
        }
    }

    // 根据老密码重置密码
    public function resetPassword()
    {
        // 接收并过滤重置密码的旧密码、新密码
//        $id = $this->request->post('id');
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $id = $user->id;

        $old_password = $this->request->post('old_password');
        $new_password = $this->request->post('new_password');

        $validate = new ResetPasswordValidate();
        if (!$validate->check(['id' => $id, 'old_password' => $old_password, 'new_password' => $new_password])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_RESETPASSWORD_NOT_STANDARD);
        }

        // 验证对应用户的密码是否正确
        $user_service = new UserService();
        $user_service->verifyPassword($id, $old_password);

        // 重置用户密码
        $user_service->updatePassword($id, $new_password);
        return ResponseCode::success(true);
    }

    // 上传头像
    public function headPortrait()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $id = $user->id;
        $portrait = $this->request->file('portrait');

        if (true !== $this->validate(['image' => $portrait], ['image' => 'require|image'])) {
            throw new UserException('请选择图像上传', ResponseCode::$USER_NOT_STANDARD);
        }
        // 将上传图像生成缩略图，并保存到指定位置
        $upload_service = new UploadService();
        $thumb_path = $upload_service->thumb($portrait);

        // 将缩略图路径保存到数据表中
        $user_service = new UserService();
        $user_service->saveThumb($id, $thumb_path);
        return ResponseCode::success($thumb_path);
    }

    // 修改用户名
    public function editName()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $id = $user->id;
        $name = $this->request->post('name');

        // 利用UserValidate验证器验证用户名
        $validate = new UserValidate();
        if (!$validate->scene('editName')->check(['name' => $name])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 修改用户信息
        $user_service = new UserService();
        $user_service->modifyName($id, $name);
        return ResponseCode::success(true);
    }

    // 向用户邮箱发送链接修改邮箱
    public function editEmail()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $id = $user->id;
        $email = $this->request->post('email');

        // 利用UserValidate验证器验证邮箱
        $validate = new UserValidate();
        if (!$validate->scene('editEmail')->check(['email' => $email])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        $user_service = new UserService();

        if( $user_service->getUserByEmail($email)){
            throw new UserException('已存在，请重新设置', ResponseCode::$USER_NOT_EXIST);
        }
        $user_service->sendEmailForResetEmail($id, $email);
        return ResponseCode::success(true);
    }

    // 验证用户链接，修改用户邮箱并发送激活链接
    public function updateEmail()
    {
        $key = $this->request->get('key');

        $id = Cache::get('update_email_id_' . $key);
        $email = Cache::get('update_email_' . $key);
        if (!$id) {
            $msg ='验证信息已过期或非法输入，请重新激活账户';
            $this->assign('msg',$msg);
            return $this->fetch("tips");
        }
        $user_service = new UserService();
        if($user_service->modifyEmail($id, $email)){
            $msg ='邮箱修改认证成功，请返回网站继续进行操作';
        }else{
            $msg ='修改错误';
        }
        Cache::set('update_email_id_' . $key, null);
        Cache::set('update_email_' . $key, null);
        $this->assign('msg',$msg);
        return $this->fetch("tips");
    }
}
