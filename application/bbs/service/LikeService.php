<?php

namespace app\bbs\service;

use app\bbs\model\LikeModel;
use app\bbs\model\TopicModel;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;
use think\Db;

class LikeService
{
    public function getLike($topic_id, $user_id)
    {
        $id = LikeModel::field('id')
          ->where('topic_id', '=', $topic_id)
          ->where('user_id', '=', $user_id)
          ->find();
        return !empty($id);
    }

    public function addLike($topic_id, $user_id)
    {
        if ($this->getLike($topic_id, $user_id)) {
            throw new UserException('已经点赞过，不能再次点赞', ResponseCode::$LIKE_IS_EXISTS);
        }

        Db::startTrans();
        try {
            $newlike = LikeModel::create([
              'topic_id' => $topic_id,
              'user_id' => $user_id
            ], ['topic_id', 'user_id']);
            if (!$newlike) {
                throw new UserException('点赞失败', ResponseCode::$LIKE_ADD_FAILED);
            }
            $topic_model = new TopicModel();
            $topic_model->where('id', '=', $topic_id)->setInc('likenum');
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
        return $topic_model->field('likenum')->where('id', '=', $topic_id)->select();
    }

    public function delLike($topic_id, $user_id)
    {
        Db::startTrans();
        try {
            if (!LikeModel::where('topic_id', '=', $topic_id)->where('user_id', '=', $user_id)->delete()) {
                throw new UserException('取消点赞失败', ResponseCode::$LIKE_DEL_FAILED);
            }
            $topic_model = new TopicModel();
            $topic_model->where('id', '=', $topic_id)->setDec('likenum');
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            throw $e;
        }
        return $topic_model->field('likenum')->where('id', '=', $topic_id)->select();
    }
}
