<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:03
 */
namespace app\bbs\controller;

use think\Controller;
use app\bbs\validate\TopicValidate;
use app\bbs\service\TopicService;
use app\bbs\exception\TopicException;
use app\bbs\common\ResponseCode;

class TopicController extends Controller
{
    public function add()
    {
        $data['name'] = $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');
        $data['category_id']  = $this->request->post('category_id', '', 'htmlspecialchars,strip_tags,trim');
        $data['user_id '] = $this->request->post('user_id', '', 'htmlspecialchars,strip_tags,trim');
        $data['content '] = $this->request->post('content', '', 'htmlspecialchars,strip_tags,trim');

        $validate = new TopicValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new TopicException($validate->getError(),ResponseCode::$TOPIC_CATE_IS_MUST);
        }

        $topic_service = new TopicService;
        $topic = $topic_service->addTopic($data);
        return  ResponseCode::success($topic);
    }

    public function list()
    {

    }
    public function edit()
    {

    }
    public function delete()
    {

    }
}