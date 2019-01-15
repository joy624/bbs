<?php

namespace app\test\controller;

use think\Controller;

class Index extends Controller
{
    // 前置方法，判断用户是否登录
    protected function initialize()
    {
        if (!session('id') || !session('name')) {
            $this->error('您尚未登录系统', url('test/login/index'));
        }
    }

    // 登录首页
    public function index()
    {
        return $this->fetch();
    }
}
