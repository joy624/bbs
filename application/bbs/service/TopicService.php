<?php

namespace app\bbs\service;

use app\bbs\common\Constants;
use app\bbs\model\UserModel;
use app\bbs\model\TopicModel;
use app\bbs\model\CategoryModel;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class TopicService
{
    public function addTopic($title, $category_id, $user_id, $content)
    {
        $auth_service = new AuthService();
        $user = $auth_service->getLoginUser();
        if (empty($user)) {
            throw new UserException('未登录', ResponseCode::$USER_NOT_LOGIN);
        }

        // 判断分类是否存在
        if (!CategoryModel::get($category_id)) {
            throw new UserException('分类不存在', ResponseCode::$CATE_NOT_EXIST);
        }

        // 添加主题
        $topic = TopicModel::create([
            'title' => $title,
            'category_id' => $category_id,
            'user_id' => $user_id,
            'content' => $content,
        ], ['title', 'category_id', 'user_id', 'content']);
        if (!$topic) {
            throw new UserException('添加主题失败', ResponseCode::$TOPIC_ADD_FAILED);
        }
        $topic = TopicModel::get($topic->id);
        $topic->uname = UserModel::field('name')->get($user_id)->name;

        return $topic;
    }

    public function editTopic($id, $title, $category_id, $user_id, $content)
    {
        $topic = TopicModel::get($id);
        // 判断修改的主题是否存在
        if (!$topic) {
            throw new UserException('主题不存在', ResponseCode::$TOPIC_NOT_EXIST);
        }

        // 判断分类是否存在
        if (!CategoryModel::get($category_id)) {
            throw new UserException('分类不存在', ResponseCode::$CATE_NOT_EXIST);
        }
        // 修改主题
        $topic_model = new TopicModel();
        if (!$topic_model->save(['title' => $title, 'category_id' => $category_id, 'content' => $content, 'user_id' => $user_id], ['id' => $id])) {
            throw new UserException('修改主题失败', ResponseCode::$TOPIC_EDIT_FAILED);
        }
        $topic->uname = UserModel::field('name')->get($user_id)->name;

        return $topic;
    }

    public function deleteTopic($id)
    {
        if (!TopicModel::get($id)) {
            throw new UserException('主题不存在', ResponseCode::$TOPIC_NOT_EXIST);
        }
        // 将主题的is_show设置为0，实现软删除
        $topic_model = new TopicModel();
        if (!$topic_model->save(['is_show' => Constants::IS_NOT_SHOW], ['id' => $id])) {
            throw new UserException('删除主题失败', ResponseCode::$TOPIC_DELETE_FAILED);
        }
    }

    public function getTopic($id)
    {
        $topic = TopicModel::withJoin(['user' => ['name', 'img_url']])
            ->where('is_show', '=', 1)
            ->get($id);
        // 根据id获取主题的对应内容
        if (!$topic) {
            throw new UserException('获取主题内容失败', ResponseCode::$TOPIC_CONTENT_ERROR);
        }

        return $topic;
    }

    public function pageTopic($category_id, $page, $pagesize = Constants::PAGE_SIZE)
    {
        return TopicModel::withJoin(['user' => ['name', 'img_url']])
            ->where('category_id', '=', $category_id)
            ->where('is_show', '=', Constants::IS_SHOW)
            ->page($page)
            ->limit($pagesize)
            ->order('id', 'DESC')
            ->select();
    }

    public function bestTopic($category_id, $count = 5)
    {
        return TopicModel::withJoin(['user' => ['name', 'img_url']])
            ->where('category_id', '=', $category_id)
            ->where('is_show', '=', Constants::IS_SHOW)
            ->limit($count)
            ->order('hits', 'DESC')
            ->select();
    }

    public function newestTopic($category_id, $count = 5)
    {
        return TopicModel::withJoin(['user' => ['name', 'img_url']])
            ->where('category_id', '=', $category_id)
            ->where('is_show', '=', Constants::IS_SHOW)
            ->limit($count)
            ->order('id', 'DESC')
            ->select();
    }

    public function getCateTopicNum($category_id, $is_show = Constants::IS_SHOW)
    {
        return TopicModel::where('category_id', '=', $category_id)
            ->where('is_show', '=', $is_show)->count();
    }

    // 增加点击量
    public function incrHits($id)
    {
        $topic_model = new TopicModel();
        $topic_model->where('id', '=', $id)->setInc('hits');
    }

//    // 增加点赞量
//    public function incrLike($id)
//    {
//        $topic_model = new TopicModel();
//        $topic_model->where('id', '=', $id)->setInc('likenum');
//
//        return $topic_model->field('likenum')->where('id', '=', $id)->select();
//}
//
//    // 取消点赞
//    public function decrLike($id)
//    {
//        $topic_model = new TopicModel();
//        $topic_model->where('id', '=', $id)->setDec('likenum');
//
//        return $topic_model->field('likenum')->where('id', '=', $id)->select();
//    }
}
