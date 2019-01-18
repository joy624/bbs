<?php
namespace app\bbs\controller;

use app\bbs\common\ResponseCode;
use app\bbs\exception\LoginException;
use app\bbs\service\AuthService;
use app\bbs\validate\UserValidate;
use think\Controller;

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
            throw new LoginException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        $user_service = new AuthService();
        $user_service->login($name, $password);
        return ResponseCode::success(true);
    }

    // 退出登录
    public function logout()
    {

        $id = $this->request->post('id');
        $name = $this->request->post('name');

        $user_service = new AuthService();
        $user_service->logout($id,$name);
        return ResponseCode::success(true);
    }

    // 获取当前登录人信息
    public function user()
    {
        $id = $this->request->post('id');
        $user_service = new AuthService();
        $user = $user_service->getLoginUser($id);
        if (!$user) {
            throw new LoginException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        return ResponseCode::success($user);
    }
}
