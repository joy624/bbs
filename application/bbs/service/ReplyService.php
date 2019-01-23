<?php
namespace app\bbs\service;

use app\bbs\validate\ReplyValidate;
use app\bbs\model\ReplyModel;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class ReplyService
{
    // 添加回复
    public function addReply($topic_id, $content)
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $user_id = $user->id;

        $validate = new ReplyValidate();
        if (!$validate->scene('add')->check(['topic_id'=>$topic_id,'content'=>$content,'user_id'=>$user_id])) {
            throw new UserException($validate->getError(), ResponseCode::$REPLY_IS_MUST);
        }

        $reply = ReplyModel::create([
            'topic_id'  =>  $topic_id,
            'content'   =>  $content,
            'user_id'   =>  $user_id
        ], ['topic_id','content','user_id']);
        if (!$reply) {
            throw new ReplyException('添加回复失败', ResponseCode::$REPLY_ADD_FAILED);
        }
        $reply = ReplyModel::withJoin(['user' => ['name', 'img_url']])
            ->get($reply->id);
        return $reply;
    }

    // 编辑回复内容
    public function editReply($id, $content)
    {
        $reply = ReplyModel::get($id);
        // 判断修改的回复是否存在
        if (!$reply) {
            throw new UserException('回复不存在', ResponseCode::$REPLY_NOT_EXIST);
        }
        $reply->save(['content' => $content], ['id' => $id]);
        return $this->getReply($id);
    }

    // 根据id获取主题的对应内容
    public function getReply($id)
    {
        $reply = ReplyModel::withJoin(['user' => ['name', 'img_url']])
            ->where('is_show', '=', 1)->get($id);
        if (!$reply) {
            throw new UserException('获取回复内容失败', ResponseCode::$REPLY_CONTENT_ERROR);
        }
        return $reply;
    }

    public function deleteReply($id)
    {
        if (!ReplyModel::get($id)) {
            throw new UserException('回复不存在', ResponseCode::$REPLY_NOT_EXIST);
        }
        // 回复内容is_show设置为0，软删除
        $reply_model = new ReplyModel();
        if (!$reply_model->save(['is_show'=>0], ['id'=>$id])) {
            throw new UserException('删除回复失败', ResponseCode::$REPLY_DELETE_FAILED);
        }
    }

    public function getTopicReply($topic)
    {
        return ReplyModel::withJoin(['user'    =>    ['name', 'img_url']])
            ->where('topic_id', '=', $topic)
            ->where('is_show', '=', 1)
            ->order('id', 'DESC')
            ->select();
    }
}
