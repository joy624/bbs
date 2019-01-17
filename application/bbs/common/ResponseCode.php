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
    public static $EMAIL_SEND_FAILED = 20004;    //邮件发送失败

    // 用户相关
    public static $USER_NOT_STANDARD = 30001;    // 用户的信息不符合规范，如密码、用户名、邮箱
    public static $REGISTER_ERROR = 3002;        // 注册用户错误
    public static $USERNAME_EXIST = 3003;        // 用户名已经存在
    public static $RESETPASSWORD_ERROR = 3004;   // 重置密码失败
    public static $TOKEN_ERROR = 3005;           // 保存激活码和激活码有效期失败
    public static $USER_NOT_EXIST = 3006;        // 用户不存在
    public static $TOKEN_EXPTIME = 3007;         // 激活码过期
    public static $USER_ACTIVATED = 3008;        // 用户已激活
    public static $USER_ACTIVATED_ERROR = 3009;  // 激活用户失败
    public static $SEND_EMAIL_CODE_ERROR = 3010; // 发送邮箱验证码失败
    public static $CAPTCHA_ERROR = 3011;         // 邮箱验证码验证失败
    public static $FILE_NOT_FOUND = 3012;        // 未找到上传文件
    public static $UPLOAD_ERROR = 3013;          // 上传文件失败
    public static $THUMB_URL_SAVE_ERROR = 3014;  // 在数据库中保存头像缩略图路径错误
    public static $EMAILNAME_EXIST = 3015;        // 邮箱已存在
    public static $EDIT_ERROR = 3016;        // 修改错误

    // 分类相关
    public static $CATE_NAME_IS_NULL = 40001;  // 分类名称为空
    public static $CATE_NAME_IS_EXIST = 40002; // 分类名称已经存在
    public static $CATE_FAILD = 40003;         // 操作分类失败
    public static $CATE_NOT_EXIST = 40004;     // 分类不存在
    public static $CATE_SORT_ERROR = 40005;    // 分类名不能为空，排序值必须为非负整数


}
