<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:31
 */
namespace app\bbs\service;

use app\bbs\exception\ReplyException;
use app\bbs\exception\LoginException;
use app\bbs\exception\UserException;
use app\bbs\model\ReplyModel;
use app\bbs\model\TopicModel;
use app\bbs\model\UserModel;
use app\bbs\common\ResponseCode;
use app\bbs\validate\ReplyValidate;
use think\facade\Session;

class ReplyService
{

    // 添加回复
    public function addReply($data)
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        $data['user_id'] = $user->id;

        $validate = new ReplyValidate();
        if (!$validate->scene('add')->check($data)) {
            throw new ReplyException($validate->getError(),ResponseCode::$REPLY_IS_MUST);
        }

        $reply = ReplyModel::create([
            'topic_id'  =>  $data['topic_id'],
            'content'   =>  $data['content'],
            'user_id'   =>  $data['user_id']
        ], ['topic_id','content','user_id']);
        if(!$reply){
            throw new ReplyException('添加回复失败',ResponseCode::$REPLY_ADD_FAILD);
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
        if(!$reply){
            throw new ReplyException('回复不存在', ResponseCode::$REPLY_NOT_EXIST);
        }
        $reply->save(['content' => $content], ['id' => $id]);
        return $this->getReply($id);
    }

    // 根据id获取主题的对应内容
    public function getReply($id)
    {
        $reply = ReplyModel::withJoin(['user' => ['name', 'img_url']])
            ->get($id);
        if (!$reply) {
            throw new UserException('获取主题内容失败', ResponseCode::$TOPIC_CONTENT_ERROR);
        }
        return $reply;
    }

    public function deleteReply($id)
    {
        if(!ReplyModel::get($id)){
            throw new LoginException('回复不存在', ResponseCode::$REPLY_NOT_EXIST);
        }
        // 修改主题
        $reply_model = new ReplyModel();
        if(!$reply_model->save(['is_show'=>0], ['id'=>$id])){
            throw new UserException('删除回复失败',ResponseCode::$REPLY_DELETE_FAILD);
        }
    }

    public function getTopicReply($topic)
    {
        return ReplyModel::withJoin(['user'	=>	['name', 'img_url']])
            ->where('topic_id','=',$topic)
            ->where('is_show','=',1)
            ->order('id','DESC')
            ->select();
    }

}