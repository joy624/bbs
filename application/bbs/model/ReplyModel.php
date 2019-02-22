<?php
namespace app\bbs\model;

use think\Model;

class ReplyModel extends Model
{
    public function user()
    {
        return $this->belongsTo('UserModel', 'user_id');
    }
}
