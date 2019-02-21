<?php

namespace app\bbs\controller;

use think\Controller;
use app\bbs\validate\LikeValidate;
use app\bbs\service\LikeService;
use app\bbs\service\AuthService;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class LikeController extends Controller
{
    public function index()
    {
        $topic_id = $this->request->post('topic_id');
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $user_id = $user->id;

        $like_service = new LikeService();
        $like = $like_service->getLike($topic_id, $user_id);
        return ResponseCode::success($like);
    }

    public function add()
    {
        $topic_id = $this->request->post('topic_id');
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $user_id = $user->id;

        $like_service = new LikeService();
        $like_service->addLike($topic_id, $user_id);
        return ResponseCode::success(true);
    }

    public function del()
    {
        $topic_id = $this->request->post('topic_id');
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $user_id = $user->id;

        $like_service = new LikeService();
        $like_service->delLike($topic_id, $user_id);
        return ResponseCode::success(true);
    }

}