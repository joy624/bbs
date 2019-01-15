<?php
namespace app\bbs\controller;

use think\Controller;
use think\Facade\Request;
use app\bbs\service\User as UserService;
use think\Facade\Session;
use app\bbs\common\ResultCodeMsg;

class Login extends Controller
{
    // 用户登录
    public function index()
    {
        //  判断请求是否是POST请求
        if (Request::isPost()) {
            //  获取用户的用户名和密码
            $name = $this->request->param('name','','htmlspecialchars,strip_tags');
            $password = $this->request->param('password','','htmlspecialchars,strip_tags');

            // 从模型类中获取判断结果
            $user_service = new UserService();
            $data = $user_service->login($name, $password);
            $data['data'] = null;
            return json($data);
        }
        ResultCodeMsg::$USER_PASSWORD_IS_NULL['data'] = null;
        return json(ResultCodeMsg::$USER_PASSWORD_IS_NULL);
    }
    // 退出登录
    public function logout()
    {
        Session::pull('id');
        Session::pull('name');
        ResultCodeMsg::$USER_PASSWORD_IS_NULL['data'] = null;
        return json(ResultCodeMsg::$USER_PASSWORD_IS_NULL);
    }
}
