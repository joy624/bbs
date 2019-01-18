<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:03
 */
namespace app\bbs\controller;

use app\bbs\model\TopicModel;
use think\Controller;
use app\bbs\validate\TopicValidate;
use app\bbs\service\TopicService;
use app\bbs\exception\TopicException;
use app\bbs\common\ResponseCode;

class TopicController extends Controller
{
    public function add()
    {
        $data['title'] = $this->request->post('title');
        $data['category_id']  = $this->request->post('category_id');
        $data['user_id'] = $this->request->post('user_id');
        $data['content'] = $this->request->post('content');

        $validate = new TopicValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new TopicException($validate->getError(),ResponseCode::$TOPIC_TITLE_CATE_CONTENT_IS_MUST);
        }

        $topic_service = new TopicService;
        $topic = $topic_service->addTopic($data);
        return  ResponseCode::success($topic);
    }
    public function edit()
    {
        $data['id'] = $this->request->post('id');
        $data['title'] = $this->request->post('title');
        $data['category_id']  = $this->request->post('category_id');
        $data['user_id'] = $this->request->post('user_id');
        $data['content'] = $this->request->post('content');

        $validate = new TopicValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new TopicException($validate->getError(),ResponseCode::$TOPIC_TITLE_CATE_CONTENT_IS_MUST);
        }

        $topic_service = new TopicService;
        $topic = $topic_service->editTopic($data);
        return  ResponseCode::success($topic);
    }
    public function delete()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService;
        $topic_service->deleteTopic($id);
        return  ResponseCode::success(true);
    }

    // 根据分类获取主题列表
    public function list()
    {
        $category_id = $this->request->post('category_id',1);

        $topic_service = new TopicService();
        $topics = $topic_service->cateTopic($category_id);
        return  ResponseCode::success($topics);
    }
    // 分页展示主题列表
    public function listPage()
    {
        $page = $this->request->get('page',1);

        $topic_service = new TopicService();
        $topics = $topic_service->pageTopic($page);
        return  ResponseCode::success($topics);
    }

    // 获取某个主题信息
    public function topic()
    {
        $id = $this->request->post('id');

        $topic_service = new TopicService();
        $topic = $topic_service->getTopic($id);
        // 将此主题的点击量加1
        $topic_service->addHits($id);
        return ResponseCode::success($topic);
    }

    // 点赞量
    public function likeNum()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService();
        $num = $topic_service->addLike($id);
        return ResponseCode::success($num);
    }
}