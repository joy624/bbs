<?php
namespace app\bbs\model;

use think\Model;

class UserModel extends Model
{
    public static function getSafeAttrs()
    {
        return ['id', 'name', 'email', 'mobile', 'role', 'img_url', 'reg_time'];
    }
}