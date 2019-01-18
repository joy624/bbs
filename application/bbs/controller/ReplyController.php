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
use app\bbs\validate\ReplyValidate;
use app\bbs\service\ReplyService;
use app\bbs\exception\ReplyException;
use app\bbs\common\ResponseCode;

class ReplyController extends Controller
{
    public function add()
    {
        $data['topic_id'] = $this->request->post('topic_id');
        $data['content']  = $this->request->post('content');
        $data['user_id'] = $this->request->post('user_id');

        $validate = new ReplyValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new ReplyException($validate->getError(),ResponseCode::$REPLY_IS_MUST);
        }

        $reply_service = new ReplyService();
        $reply = $reply_service->addReply($data);
        return  ResponseCode::success($reply);
    }
    public function edit()
    {
        $data['id'] = $this->request->post('id');
        $data['topic_id'] = $this->request->post('topic_id');
        $data['content']  = $this->request->post('content');
        $data['user_id'] = $this->request->post('user_id');

        $validate = new ReplyService();
        if (!$validate->scene('add')->check($data)) {
            throw new ReplyException($validate->getError(),ResponseCode::$REPLY_IS_MUST);
        }

        $reply_service = new ReplyService();
        $reply = $reply_service->editReply($data);
        return  ResponseCode::success($reply);
    }
    public function delete()
    {
        $id = $this->request->post('id');
        $topic_service = new TopicService;
        $topic_service->deleteTopic($id);
        return  ResponseCode::success(true);
    }

    // 根据对应主题的回复列表
    public function list()
    {
        $category_id = $this->request->post('category_id',1);

        $topic_service = new TopicService();
        $topics = $topic_service->cateTopic($category_id);
        return  ResponseCode::success($topics);
    }

}