<?php
namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\SystemException;
use think\facade\Cache;

class RegisterService
{
    public function register($name, $password, $email)
    {
        // 1、添加用户
        $user_service = new UserService();
        $user = $user_service->addUser(
            $name,
            $password,
            $email
        );

        // 2、邮箱发送激活链接
        $subject = '用户帐号激活';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的" . $name . "：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/> 
    <a href='http://my.test.tp/bbs/user/activateAccount?key=" . $key . "' target='_blank'> http://my.test.tp/bbs/user/activateAccount?key=" . $key . "</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, $name, $subject, $body);

        if (!Cache::set('activate_email_url_' . $key, $user->id, 24 * 60 * 60)) {
            throw new SystemException('账户激活发送失败，请重试或联系管理员', ResponseCode::$EMAIL_ACTIVATE_KEY_SAVE_FAILED);
        }

        return $user;
    }
}