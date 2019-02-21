<?php

namespace app\bbs\controller;

use think\Controller;
use app\bbs\validate\CateValidate;
use app\bbs\service\CategoryService;
use app\bbs\service\AuthService;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class CategoryController extends Controller
{
    public function add()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        if ($user->role !== 'admin') {
            throw new UserException('此用户没有操作分类的权限', ResponseCode::$USER_NOT_ADMIN);
        }

        $name = $this->request->post('name');

        $validate = new CateValidate();
        if (!$validate->scene('add')->check(['name' => $name])) {
            throw new UserException($validate->getError(), ResponseCode::$CATE_NAME_IS_NULL);
        }

        $cate_service = new CategoryService;
        $cate = $cate_service->addCate($name);
        return ResponseCode::success($cate);
    }

    public function edit()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        if ($user->role !== 'admin') {
            throw new UserException('此用户没有操作分类的权限', ResponseCode::$USER_NOT_ADMIN);
        }

        $id = $this->request->post('id');
        $name = $this->request->post('name');
        $sort = $this->request->post('sort');

        $validate = new CateValidate();
        if (!$validate->scene('edit')->check(['name' => $name, 'sort' => $sort])) {
            throw new UserException($validate->getError(), ResponseCode::$CATE_SORT_ERROR);
        }

        $cate_service = new CategoryService;
        $cate_service->editCate($id, $name, $sort);
        return ResponseCode::success(true);
    }

    public function delete()
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        if ($user->role !== 'admin') {
            throw new UserException('此用户没有操作分类的权限', ResponseCode::$USER_NOT_ADMIN);
        }

        $id = $this->request->post('id');

        $cate_service = new CategoryService;
        $cate_service->deleteCate($id);
        return ResponseCode::success(true);
    }

    public function index()
    {
        $cate_service = new CategoryService;
        $data = $cate_service->listCate();
        return ResponseCode::success($data);
    }
}
