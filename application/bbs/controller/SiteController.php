<?php
namespace app\bbs\controller;

use app\bbs\common\ResponseCode;
use app\bbs\exception\LoginException;
use app\bbs\service\UserService;
use app\bbs\validate\RegisterValidate;
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

        $name = $this->request->post('name','','htmlspecialchars,strip_tags,trim');
        $password = $this->request->post('password','','htmlspecialchars,strip_tags,trim');
        if (empty($name) || empty($password)) {
            throw new LoginException('用户名和密码不能为空', ResponseCode::$NAME_PASSWORD_IS_NULL);
        }
        $validate = new RegisterValidate();
        if (!$validate->check(['name' => $name, 'password' => $password])) {
            throw new LoginException($validate->getError(), 1000);
        }

        $user_service = new UserService();
        $user_service->login($name, $password);
        return ResponseCode::success(true);
    }

    // 退出登录
    public function logout()
    {
        $user_service = new UserService();
        $user_service->logout();
        return ResponseCode::success(true);
    }

    // 获取当前登录人信息
    public function user()
    {
        $user_service = new UserService();
        $user = $user_service->getLoginUser();
        if (!$user) {
            throw new LoginException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        return ResponseCode::success();
    }

}
