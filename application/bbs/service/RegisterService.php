<?php
/**
 * Created by PhpStorm.
 * User: lihz
 * Date: 2019/2/12
 * Time: 11:07 AM
 */

namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\SystemException;
use app\bbs\exception\UserException;
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

    public function sendEmailForFindingPass($email)
    {
        $user_service = new UserService();
        $user = $user_service->getUserByEmail($email);
        if (!$user) {
            throw new UserException('邮箱未注册', ResponseCode::$USER_NOT_EXIST);
        }

        $subject = '找回密码邮件';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的" . $user->name . "：<br />请点击下面的链接来重置您的密码。<br />
 <a href='http://my.test.tp/bbs/user/updatePwd?key=" . $key . "' target='_blank'>http://my.test.tp/bbs/user/updatePwd?key=" . $key . "</a><br/> 
如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。<br />该验证邮件有效期为30分钟，超时请重新发送邮件。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, $user->name, $subject, $body);

        if (!Cache::set('validate_email_url_' . $key, $user->id, 30 * 60)) {
            throw new SystemException('找回密码失败，请重试或联系管理员', ResponseCode::$FIND_PASSWORD_FAILED);
        }
    }

    // 向用户的邮箱中发送验证链接
    public function sendEmailForResetEmail($id, $email)
    {
        $subject = '修改用户邮箱链接';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的用户：<br/>若想要修改邮箱，请点击以下链接。<br/> 
    <a href='http://my.test.tp/bbs/user/updateEmail?key=" . $key . "' target='_blank'> http://my.test.tp/bbs/user/updateEmail?key=" . $key . "</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接30分钟内有效。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, '', $subject, $body);

        if (!Cache::set('update_email_url_' . $key, $id, 30 * 60)) {
            throw new SystemException('发送修改链接失败，请重试或联系管理员', ResponseCode::$EMAIL_SEND_FAILED);
        }
    }
}