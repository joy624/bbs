<?php
namespace app\bbs\common;

class ResponseCode
{

    public static function success($data) {
        return json(['code' => 200, 'msg' => null, 'data' => $data]);
    }

    // 登录相关
    public static $NAME_PASSWORD_IS_NULL = 20001;//用户名或密码为空
    public static $USER_DOES_NOT_EXIST = 20002;  //用户不存在
    public static $PASSWORD_ERROR = 20003;       //密码错误
    public static $USER_NOT_LOGIN = 20004;       //未登录

}
