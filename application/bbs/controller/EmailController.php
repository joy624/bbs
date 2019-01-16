<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-16
 * Time: 17:36
 */
namespace app\bbs\controller;

use app\bbs\exception\UserException;
use think\Controller;
use PHPMailer\PHPMailer\PHPMailer;
use app\bbs\common\ResponseCode;
use app\bbs\service\UserService;
use app\bbs\exception\RegisterException;

class EmailController extends Controller
{
    // 发送邮件
    private function sendEmail($toemail, $name, $subject = '', $body = '')
    {
        $mail = new PHPMailer();        // 实例化PHPMailer对象

        $mail->CharSet = 'UTF-8';       // 设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
        $mail->isSMTP();                // 设定使用SMTP服务
        $mail->SMTPAuth = true;        // 启用SMTP验证功能
        $mail->SMTPSecure = 'ssl';      // 使用安全协议

        $mail->Host = "smtp.163.com";               // SMTP 服务器
        $mail->Port = 465;                          // SMTP服务器的端口号
        $mail->Username = '18311094611@163.com';    // SMTP服务器用户名
        $mail->Password = 'qiaozhiming183';         // SMTP服务器密码
        $mail->SetFrom($mail->Username , 'admin');  // 设置发件人信息

        $replyEmail = '';                           // 留空则为发件人EMAIL
        $replyName = '';                            // 回复名称（留空则为发件人名称）
        $mail->AddReplyTo($replyEmail, $replyName); // 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址

        $mail->Subject = $subject;                  // 邮件标题
        $mail->MsgHTML($body);                      // 邮件正文
        $mail->AddAddress($toemail, $name);         // 设置收件人信息

        return $mail->Send() ? true : $mail->ErrorInfo;
    }

    // 向邮箱发送激活码链接
    public function sendEmailURL($id, $email, $name){
        $subject = '用户帐号激活';
        // 设置账户激活码
        $token_exptime = time();
        $token = md5($name.$token_exptime.mt_rand(100000,999999));
        $body= "亲爱的".$name."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/> 
    <a href='http://my.test.tp/bbs/email/validateEmail?token=".$token."' target= 
'_blank'>http://my.test.tp/bbs/email/validateEmail?token=".$token."</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。";

        // 向指定账户发送邮件
        $res=$this->sendEmail($email,$name,$subject,$body);

        if($res){
            // 保存账户激活码和激活码有效期
            $user_service = new UserService();
            $user_service->addToken($id,$token,$token_exptime);
            return ResponseCode::success(true);
        }else{
            throw new UserException('邮件发送失败，请重试', ResponseCode::$EMAIL_SEND_FAILED);
        }
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

    // 激活邮箱
    public function validateEmail()
    {
        // 接收激活码
        $token = $this->request->get('token', '', 'htmlspecialchars,strip_tags,trim');

        // 验证激活码
        $user_service = new UserService();
        $data = $user_service->validateToken($token);
        return ResponseCode::success(true);
    }
}