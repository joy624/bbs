<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:14
 */
namespace app\bbs\model;

use think\Model;

class TopicModel extends Model
{
    public function user()
    {
        return $this->belongsTo('UserModel','user_id');
    }
}