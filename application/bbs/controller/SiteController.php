<?php

namespace app\bbs\controller;

use think\Controller;
use app\bbs\service\AuthService;
use app\bbs\validate\UserValidate;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class SiteController extends Controller
{
    public function index()
    {
        echo '后续这里显示前端页面';
    }

    // 用户登录
    public function login()
    {
        // 获取并过滤登录的用户名和密码
        $name = $this->request->post('name');
        $password = $this->request->post('password');

        // 利用UserValidate验证器验证用户名和密码是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('login')->check(['name' => $name, 'password' => $password])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        $user_service = new AuthService();
        $user_service->login($name, $password);
        return ResponseCode::success(true);
    }

    // 获取当前登录人信息
    public function user()
    {
        $user_service = new AuthService();
        $user = $user_service->getLoginUser();
        return ResponseCode::success($user);
    }

    // 退出登录
    public function logout()
    {
        $user_service = new AuthService();
        $user_service->logout();
        return ResponseCode::success(true);
    }
}
