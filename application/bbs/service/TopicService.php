<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:31
 */
namespace app\bbs\service;

use app\bbs\exception\CategoryException;
use app\bbs\exception\UserException;
use app\bbs\model\ReplyModel;
use app\bbs\model\TopicModel;
use app\bbs\model\UserModel;
use app\bbs\exception\TopicException;
use app\bbs\exception\LoginException;
use app\bbs\common\ResponseCode;
use think\facade\Session;
use app\bbs\model\CategoryModel;

class TopicService
{
    public function addTopic($data)
    {
        // 判断用户是否登录（登录的用户一定是激活的用户）
        if((int)$data['user_id'] !== Session::get('id_'.$data['user_id'])){
            throw new LoginException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }
        // 判断分类是否存在
        if(!CategoryModel::get($data['category_id'])){
            throw new CategoryException('分类不存在',ResponseCode::$CATE_NOT_EXIST);
        }

        // 添加主题
        $topic = TopicModel::create([
            'title'         =>  $data['title'],
            'category_id'   =>  $data['category_id'],
            'user_id'       =>  $data['user_id'],
            'content'       =>  $data['content']
        ],['title','category_id','user_id','content']);
        if(!$topic){
            throw new CategoryException('添加主题失败',ResponseCode::$TOPIC_ADD_FAILD);
        }
        $topic = TopicModel::get($topic->id);
        $topic->uname =  UserModel::field('name')->get($data['user_id'])->name;
        return $topic;
    }
    public function editTopic($data)
    {
        $topic = TopicModel::get($data['id']);
        // 判断修改的主题是否存在
        if(!$topic){
            throw new LoginException('主题不存在', ResponseCode::$TOPIC_NOT_EXIST);
        }

        // 判断分类是否存在
        if(!CategoryModel::get($data['category_id'])){
            throw new CategoryException('分类不存在',ResponseCode::$CATE_NOT_EXIST);
        }
        // 修改主题
        $topic_model = new TopicModel();
        if(!$topic_model->save(['title'=>$data['title'], 'category_id'=>$data['category_id'], 'content'=>$data['content'], 'user_id'=>$data['user_id']],['id'=>$data['id']])){
            throw new CategoryException('修改主题失败',ResponseCode::$TOPIC_EDIT_FAILD);
        }
        $topic->uname =  UserModel::field('name')->get($data['user_id'])->name;
        return $topic;
    }
    public function deleteTopic($id)
    {
        if(!TopicModel::get($id)){
            throw new LoginException('主题不存在', ResponseCode::$TOPIC_NOT_EXIST);
        }
        // 修改主题
        $topic_model = new TopicModel();
        if(!$topic_model->save(['is_show'=>0],['id'=>$id])){
            throw new CategoryException('删除主题失败',ResponseCode::$TOPIC_DELETE_FAILD);
        }
    }
    public function getTopic($id)
    {
        // 根据id获取主题的对应内容
        $topic = TopicModel::get($id);
        if (!$topic) {
            throw new TopicException('获取主题内容失败', ResponseCode::$TOPIC_CONTENT_ERROR);
        }
        // 获取用户名
        $topic->uname = UserModel::field('name')->get($topic['user_id'])->name;
        // 获取主题的回复
        $reply_model = new ReplyModel();
        $topic->reply = $reply_model->where('topic_id',$id)
            ->where('is_show','=',1)->find();
        return $topic;
    }

    public function cateTopic($category_id)
    {
        return TopicModel::withJoin(['user'	=>	['name', 'img_url']])
            ->where('category_id','=',$category_id)
            ->where('is_show','=',1)
            ->limit(2)
            ->order('update_time','DESC')
            ->select();
    }
    public function addHits($id)
    {
        // 增加点击量
        $topic_model = new TopicModel();
        $topic_model->where('id','=',$id)->setInc('hits');
    }
    public function addLike($id)
    {
        // 增加点赞量
        $topic_model = new TopicModel();
        $topic_model->where('id','=',$id)->setInc('likenum');
        return $topic_model->field('likenum')->where('id','=',$id)->select();
    }
}