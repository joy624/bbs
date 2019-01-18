<?php
namespace app\bbs\model;

use think\Model;

class UserModel extends Model
{
    public static function getSafeAttrs()
    {
        return ['id', 'name', 'email', 'role', 'img_url', 'reg_time', 'is_active', 'update_time'];
    }

}