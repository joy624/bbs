<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/15
 * Time: 下午11:25
 */

namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class EmailService
{
    // 向邮箱发送激活码链接
    public function sendEmailURL($email,$name, $subject, $body,$key)
    {
        // 向指定账户发送邮件
        $res=$this->sendEmail($email,$name,$subject,$body);
        if(!$res){
            throw new UserException('邮件发送失败，请重试', ResponseCode::$EMAIL_SEND_FAILED);
        }
    }

    // 激活邮箱
    public function validateEmail()
    {
        // 接收激活码
        $token = $this->request->get('token');

        // 验证激活码
        $user_service = new UserService();
        $data = $user_service->validateToken($token);
        return ResponseCode::success(true);
    }

    // 向邮箱发送验证码
    public function sendEmailCode($email,$name){
        $subject='忘记密码的验证码';
        $code = mt_rand(100000,999999);
        $body='亲爱的'.$name.'用户,您的验证码是：'.$code;
        $res=send_mail($email,$name,$subject,$body);
        if($res){
            session('emailCode',$code);// 记录邮件验证码
            return ResponseCode::success(true);
        }else{
            throw new RegisterException('', ResponseCode::$SEND_EMAIL_CODE_ERROR);
        }
    }
}