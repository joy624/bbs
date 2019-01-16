<?php
namespace app\bbs\controller;

use app\bbs\exception\UserException;
use app\bbs\validate\ResetPasswordValidate;
use think\Controller;
use app\bbs\validate\UserValidate;
use app\bbs\exception\RegisterException;
use app\bbs\common\ResponseCode;
use app\bbs\service\UserService;

class UserController extends Controller
{
    // 用户注册
    public function register()
    {
        // 接收并过滤注册的用户名、密码和邮箱
        $name = $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');
        $password = $this->request->post('password', '', 'htmlspecialchars,strip_tags,trim');
        $email = $this->request->post('email', '', 'htmlspecialchars,strip_tags,trim');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->check(['name' => $name, 'password' => $password, 'email'=>$email])) {
            throw new RegisterException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 添加用户
        $user_service = new UserService();
        $user = $user_service->add($name, $password,$email);
        return ResponseCode::success($user);
    }

    // 重置密码
    public  function resetPassword()
    {
        // 接收并过滤重置密码的用户id、旧密码、新密码
        $id = $this->request->post('id', '', 'htmlspecialchars,strip_tags,trim');
        $old_password = $this->request->post('old_password', '', 'htmlspecialchars,strip_tags,trim');
        $new_password = $this->request->post('new_password', '', 'htmlspecialchars,strip_tags,trim');

        $validate = new ResetPasswordValidate();
        if (!$validate->check(['id' => $id, 'old_password' => $old_password, 'new_password' => $new_password])) {
            throw new UserException($validate->getError(), ResponseCode::$RESETPASSWORD_ERROR);
        }

        // 验证对应用户的密码是否正确
        $user_service = new UserService();
        $user_service->verifyPassword($id, $old_password);

        // 重置用户密码
        $user_service->updatePassword($id, $new_password);
        return ResponseCode::success(true);
    }

    // 忘记密码
    public function findPassword()
    {
        $name =  $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');
        $email =  $this->request->post('email', '', 'htmlspecialchars,strip_tags,trim');
        $new_password=  $this->request->post('new_password', '', 'htmlspecialchars,strip_tags,trim');
        $code =  $this->request->post('code', '', 'htmlspecialchars,strip_tags,trim');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->check(['name' => $name, 'password' => $new_password, 'email'=>$email])) {
            throw new RegisterException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }
        // 判断邮箱验证码是否正确
        if( session('emailCode') !== (int)$code) {
            return ResponseCode::$CAPTCHA_ERROR;
        }

        // 修改用户密码
        $user_service = new UserService();
        $user = $user_service->getUserByName($name);

        $user_service->updatePassword($user->id, $new_password);
        return ResponseCode::success(true);
    }

    // 上传头像
    public function headPortrait()
    {
    }

}