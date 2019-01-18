<?php
namespace app\bbs\controller;

use think\Controller;
use PHPMailer\PHPMailer\PHPMailer;

class EmailController extends Controller
{
    // 发送邮件
    private function sendEmail($toemail, $name = '', $subject = '', $body = '')
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
}