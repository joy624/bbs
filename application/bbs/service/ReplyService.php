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
use app\bbs\model\ReplyModel;
use app\bbs\model\TopicModel;
use app\bbs\model\UserModel;
use app\bbs\common\ResponseCode;
use think\facade\Session;

class ReplyService
{

    public function addReply($data)
    {
        // 判断用户是否登录（登录的用户一定是激活的用户）
        if((int)$data['user_id'] !== Session::get('id_'.$data['user_id'])){
            throw new LoginException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }

        // 添加回复
        $reply = ReplyModel::create([
            'topic_id'         =>  $data['topic_id'],
            'content'   =>  $data['content'],
            'user_id'       =>  $data['user_id']
        ],['topic_id','content','user_id']);
        if(!$reply){
            throw new ReplyException('添加回复失败',ResponseCode::$REPLY_ADD_FAILD);
        }
        $reply = ReplyModel::get($reply->id);
        $reply->uname =  UserModel::field('name')->get($data['user_id'])->name;
        return $reply;
    }
    public function editReply($data)
    {
        $reply = ReplyModel::get($data['id']);
        // 判断修改的回复是否存在
        if(!$reply){
            throw new ReplyException('回复不存在', ResponseCode::$REPLY_NOT_EXIST);
        }

        // 判断主题是否存在
        if(!TopicModel::where('is_show','=',1)->get($data['topic_id'])){
            throw new ReplyException('分类不存在',ResponseCode::$TOPIC_NOT_EXIST);
        }

    }
}