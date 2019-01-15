<?php

namespace app\test\controller;

use think\Controller;
use think\Facade\Request;
use app\test\model\User as UserModel;
use think\Facade\Session;

class Login extends Controller
{
    // 用户登录
    public function index()
    {
        //  判断请求是否是 POST 请求
        if (Request::isPost()) {
            //  获取用户的用户名和密码
            $name = $this->request->param('uname');
            $pwd = $this->request->param('pwd');
            // 从模型类中获取判断结果
            $user_model = new UserModel();
            $result = $user_model->login($name, $pwd);
            // 根据不同的判断结果执行不同的操作
            switch ($result) {
                case ResultCode::$LOGIN_SUCCESS:
                    $this->success('用户登录成功！', url('test/index/index'));
                    break;

                case ResultCode::$USER_DOES_NOT_EXIST:
                    $this->error('用户名不存在！');
                    break;

                case ResultCode::$PASSWORD_ERROR:
                    $this->error('密码错误，请确认后再次输入！');
                    break;
            }
        }
        return $this->fetch();
    }

    // 退出登录
    public function logout()
    {
        Session::pull('id');
        Session::pull('name');
        $this->redirect('test/login/index');
    }
}
