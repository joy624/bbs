<?php
namespace app\bbs\common;

class ResultCodeMsg
{
    public static $SUCCESS = ['code'=>200,'msg'=>'操作成功'];                  // 成功
    public static $USER_DOES_NOT_EXIST = ['code'=>2001,'msg'=>'用户名不存在'];           // 用户名不存在
    public static $PASSWORD_ERROR = ['code'=>2002,'msg'=>'密码输入错误'];                // 密码错误
    public static $USER_PASSWORD_IS_NULL = ['code'=>2003,'msg'=>'用户和密码不能为空'];   // 用户和密码为空
    public static $SEND_ERROR = ['code'=>2004,'msg'=>'发送失败'];                        // 发送失败
    public static $CAPTCHA_ERROR = ['code'=>2005,'msg'=>'验证码错误'];                   // 验证码错误
}
