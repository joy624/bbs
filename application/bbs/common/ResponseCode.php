<?php
namespace app\bbs\common;

class ResponseCode
{

    public static function success($data) {
        return json(['code' => 200, 'msg' => null, 'data' => $data]);
    }

    // 登录相关
    public static $USER_DOES_NOT_EXIST = 20001;  //用户不存在
    public static $PASSWORD_ERROR = 20002;       //密码错误
    public static $USER_NOT_LOGIN = 20003;       //未登录

    // 用户相关
    public static $USER_NOT_STANDARD = 30001;   // 用户的信息不符合规范，如密码、用户名、邮箱
    public static $REGISTER_ERROR = 3002;       // 注册用户错误
    public static $USERNAME_EXIST = 3003;       // 用户名已经存在
    public static $RESETPASSWORD_ERROR = 3004;  // 重置密码失败
    public static $TOKEN_ERROR = 3005;          // 保存激活码和激活码有效期失败
    public static $USER_NOT_EXIST = 3006;       // 用户不存在
    public static $TOKEN_EXPTIME = 3007;        // 激活码过期
    public static $USER_ACTIVATED = 3008;       // 用户已激活
    public static $USER_ACTIVATED_ERROR = 3009;    // 激活用户失败
    public static $SEND_EMAIL_CODE_ERROR = 3010;   // 发送邮箱验证码失败
    public static $CAPTCHA_ERROR = 3011;           // 邮箱验证码验证失败

}
