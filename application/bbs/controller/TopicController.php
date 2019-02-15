<?php

namespace app\bbs\controller;

use think\Controller;
use app\bbs\validate\TopicValidate;
use app\bbs\service\TopicService;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;
use app\bbs\service\AuthService;

class TopicController extends Controller
{
    public function add()
    {
        $data['title'] = $this->request->post('title');
        $data['category_id'] = $this->request->post('category_id');
//        $data['user_id'] = $this->request->post('user_id');
        $data['content'] = $this->request->post('content');

        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $data['user_id'] = $user->id;

        $validate = new TopicValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new UserException($validate->getError(), ResponseCode::$TOPIC_TITLE_CATE_CONTENT_IS_MUST);
        }

        $topic_service = new TopicService();
        $topic = $topic_service->addTopic($data['title'], $data['category_id'], $data['user_id'], $data['content']);

        return ResponseCode::success($topic);
    }

    public function edit()
    {
        $data['id'] = $this->request->post('id');
        $data['title'] = $this->request->post('title');
        $data['category_id'] = $this->request->post('category_id');
        $data['content'] = $this->request->post('content');

        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $data['user_id'] = $user->id;

        $validate = new TopicValidate();
        if (!$validate->scene('edit')->check($data)) {
            throw new UserException($validate->getError(), ResponseCode::$TOPIC_TITLE_CATE_CONTENT_IS_MUST);
        }

        $topic_service = new TopicService();
        $topic = $topic_service->editTopic($data['id'], $data['title'], $data['category_id'], $data['user_id'], $data['content']);

        return ResponseCode::success($topic);
    }

    public function delete()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService();
        $topic_service->deleteTopic($id);

        return ResponseCode::success(true);
    }

    // 根据分类获取主题列表
    public function index()
    {
        $category_id = $this->request->get('category_id', 1);
        $page = $this->request->get('page', 1);

        $topic_service = new TopicService();
        $topics = $topic_service->pageTopic($category_id, $page);

        return ResponseCode::success($topics);
    }

    // 获取某个主题信息，点击量加1
    public function view()
    {
        $id = $this->request->get('id');
        $topic_service = new TopicService();
        $topic = $topic_service->getTopic($id);
        $topic_service->incrHits($id);

        return ResponseCode::success($topic);
    }

    // 点赞操作
    public function likeNum()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService();
        $num = $topic_service->incrLike($id);

        return ResponseCode::success($num);
    }

    // 取消点赞
    public function cancelLikeNum()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService();
        $num = $topic_service->decrLike($id);

        return ResponseCode::success($num);
    }

    // 获取指定分类的总记录数
    public function getCateTopicNum()
    {
        $category_id = $this->request->get('category_id', 1);
        $topic_service = new TopicService();
        $num = $topic_service->getCateTopicNum($category_id);

        return ResponseCode::success($num);
    }
}
