<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:03
 */
namespace app\bbs\controller;

use think\Controller;
use app\bbs\service\ReplyService;
use app\bbs\exception\ReplyException;
use app\bbs\common\ResponseCode;

class ReplyController extends Controller
{

    // 添加回复
    public function add()
    {
        $params['topic_id'] = $this->request->post('topic_id');
        $params['content']  = $this->request->post('content');

        $reply_service = new ReplyService();
        $reply = $reply_service->addReply($params);
        return  ResponseCode::success($reply);
    }

    // 编辑回复
    public function edit()
    {
        $id = $this->request->post('id');
        $content = $this->request->post('content');

        $validate = new ReplyService();
        if (!$validate->scene('edit')->check(['id' => $id, 'content' => $content])) {
            throw new ReplyException($validate->getError(),ResponseCode::$REPLY_IS_MUST);
        }

        $reply_service = new ReplyService();
        $reply = $reply_service->editReply($id, $content);
        return  ResponseCode::success($reply);
    }

    // 删除回复
    public function delete()
    {
        $id = $this->request->post('id');
        $reply_service = new ReplyService;
        $reply_service->deleteReply($id);
        return  ResponseCode::success(true);
    }

    // 查看单个回复内容
    public function view()
    {
        $id = $this->request->get('id');
        $reply_service = new ReplyService();
        $reply = $reply_service->getReply($id);
        return ResponseCode::success($reply);
    }

    // 根据对应主题的回复列表
    public function index()
    {
        $topic_id = $this->request->post('topic_id');

        $reply_service = new ReplyService();
        $topics = $reply_service->getTopicReply($topic_id);
        return  ResponseCode::success($topics);
    }

}