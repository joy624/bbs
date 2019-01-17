<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:31
 */
namespace app\bbs\service;

use app\bbs\model\TopicModel;
use app\bbs\model\ReplyModel;
use app\bbs\model\UserModel;
use app\bbs\exception\CategoryException;
use app\bbs\common\ResponseCode;

class TopicService
{
    public function addTopic($data)
    {

        $topic = TopicModel::create([
            'name'=>$data['name'],
            'category_id'=>$data['category_id']
        ],['name','category_id']);
        if(!$topic){
            throw new CategoryException('操作主题标题失败',ResponseCode::$TOPIC_TITLE_FAILD);
        }
        $reply = ReplyModel::create([
            'topic_id'=>$topic->id,
            'category_id'=>$data['category_id'],
            'content'=>$data['content'],
            'user_id'=>$data['user_id']
        ],['topic_id','category_id','content','user_id']);
        if(!$reply){
            throw new CategoryException('操作主题内容和回复失败',ResponseCode::$TOPIC_REPLY_FAILD);
        }
        $user = UserModel::get($reply->user_id);
        $reply = ReplyModel::get($reply->id);
       return ['id'=>$topic->id,'name'=>$topic->name,'uname'=>$user->name,'img_url'=>$user->img_url,
           'content'=>$reply->content, 'create_time'=>$reply->create_time,
           'update_time'=>$reply->update_time,'hits'=>$reply->hits,
            'likenum'=>$reply->likenum,'is_report'=>$reply->is_report];
    }
}