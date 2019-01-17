<?php
namespace app\bbs\controller;

use app\bbs\common\ResponseCode;
use app\bbs\exception\LoginException;
use app\bbs\service\AuthService;
use app\bbs\validate\UserValidate;
use think\Controller;

class SiteController extends Controller
{
    public function Index()
    {
        echo '后续这里显示前端页面';
    }

    // 用户登录
    public function Login()
    {
        // 获取并过滤登录的用户名和密码
        $name = $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');
        $password = $this->request->post('password', '', 'htmlspecialchars,strip_tags,trim');

        // 利用UserValidate验证器验证用户名和密码是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('login')->check(['name' => $name, 'password' => $password])) {
            throw new LoginException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        $user_service = new AuthService();
        $user_service->login($name, $password);
        return ResponseCode::success(true);
    }

    // 退出登录
    public function Logout()
    {
        $user_service = new AuthService();
        $user_service->logout();
        return ResponseCode::success(true);
    }

    // 获取当前登录人信息
    public function User()
    {
        $user_service = new AuthService();
        $user = $user_service->getLoginUser();
        if (!$user) {
            throw new LoginException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        return ResponseCode::success($user);
    }
}
