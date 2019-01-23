<?php
namespace app\bbs\common;

class ResponseCode
{
    // 执行成功，返回的成功信息
    public static function success($data)
    {
        return json(['code' => 200, 'msg' => null, 'data' => $data]);
    }

    // 执行失败返回的错误代码
    // 用户相关
    public static $USER_NOT_STANDARD                = 30001;    // 用户的信息不符合规范，如密码、用户名、邮箱、头像
    public static $USER_NAME_EXIST                  = 30002;    // 用户名已经存在
    public static $USER_EMAIL_EXIST                 = 30003;    // 邮箱已经存在
    public static $USER_REGISTER_FAILED             = 30004;    // 注册用户错误
    public static $USER_ACTIVATE_KEY_ERROR          = 30005;    // 激活链接已过期或非法输入，请重新激活账户
    public static $USER_ACTIVATE_FAILED             = 30006;    // 修改用户是否激活的标志失败
    public static $USER_RESETPASSWORD_NOT_STANDARD  = 30007;    // 用户重置密码不符合规范
    public static $USER_NOT_EXIST                   = 30008;    // 用户不存在
    public static $USER_PASSWORD_ERROR              = 30009;    // 用户密码错误
    public static $USER_RESETPASSWORD_FAILED        = 30010;    // 重置用户密码错误
    public static $USER_MOVE_FILE_FAILED            = 30011;    // 将上传文件保存到指定位置失败
    public static $USER_SAVE_IMG_FAILED             = 30012;    // 保存上传头像失败
    public static $USER_EDIT_ERROR                  = 30013;    // 修改用户名或邮箱失败
    public static $USER_NOT_ACTIVE                  = 30014;    // 用户未激活
    public static $USER_NOT_LOGIN                   = 30015;    // 用户未登录

    // 邮件相关
    public static $EMAIL_SEND_FAILED                = 40001;    // 邮件发送失败
    public static $EMAIL_ACTIVATE_KEY_SAVE_FAILED   = 40002;    // 用户注册时，在Redis中保存邮箱激活码失败
    public static $FIND_PASSWORD_FAILED             = 40003;    // 找回密码时，在Redis中保存邮箱激活码失败

    // 分类相关
    public static $CATE_NAME_IS_NULL                = 50001;    // 分类名称为空
    public static $CATE_NAME_IS_EXIST               = 50002;    // 分类名称已经存在
    public static $CATE_FAILED                      = 50003;    // 操作分类失败
    public static $CATE_NOT_EXIST                   = 50004;    // 分类不存在
    public static $CATE_SORT_ERROR                  = 50005;    // 分类名不能为空，排序值必须为非负整数

    // 主题相关
    public static $TOPIC_TITLE_CATE_CONTENT_IS_MUST = 60001;    // 标题、分类和内容是必须的
    public static $TOPIC_NOT_EXIST                  = 60002;    // 主题不存在
    public static $TOPIC_ADD_FAILED                 = 60003;    // 添加主题失败
    public static $TOPIC_EDIT_FAILED                = 60004;    // 修改主题失败
    public static $TOPIC_DELETE_FAILED              = 60005;    // 删除主题失败
    public static $TOPIC_CONTENT_ERROR              = 60006;    // 获取主题内容失败

    // 回复相关
    public static $REPLY_IS_MUST                    = 70001;    // 回复内容是必须的
    public static $REPLY_ADD_FAILED                 = 70002;    // 添加回复失败
    public static $REPLY_EDIT_FAILED                = 70003;    // 修改回复失败
    public static $REPLY_DELETE_FAILED              = 70004;    // 删除回复失败
    public static $REPLY_NOT_EXIST                  = 70005;    // 回复不存在
    public static $REPLY_CONTENT_ERROR              = 70006;    // 获取回复内容失败
}
