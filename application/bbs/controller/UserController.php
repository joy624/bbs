<?php
namespace app\bbs\controller;

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
        $user_service->add($name, $password,$email);
        return ResponseCode::success(true);
    }

}