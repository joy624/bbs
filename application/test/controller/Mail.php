<?php

namespace app\test\controller;

use PHPMailer\PHPMailer\PHPMailer;
use think\Controller;

class Mail extends Controller
{
    //发送邮箱验证码
    public function email()
    {
        $toemail = 'qiaozhiming@itcast.cn'; //定义收件人的邮箱
        $mail = new PHPMailer();

        $mail->isSMTP(); // 使用SMTP服务
        $mail->CharSet = 'utf8'; // 编码格式为utf8，不设置编码的话，中文会出现乱码
        $mail->Host = 'smtp.qq.com'; // 发送方的SMTP服务器地址
        $mail->SMTPAuth = true; // 是否使用身份验证
        $mail->Username = '990198829@qq.com'; // 发送方的QQ邮箱用户名，就是自己的邮箱名
        $mail->Password = 'obgzotdlidbabcib'; // 发送方的邮箱密码，不是登录密码,是qq的第三方授权登录码,要自己去开启,在邮箱的设置->账户->POP3/IMAP/SMTP/Exchange/CardDAV/CalDAV服务 里面
        $mail->SMTPSecure = 'ssl'; // 使用ssl协议方式,
        $mail->Port = 465; // QQ邮箱的ssl协议方式端口号是465/587

        $mail->setFrom('990198829@qq.com', '来自乔治铭的问候'); // 设置发件人信息，如邮件格式说明中的发件人,
        $mail->addAddress($toemail, '来自火星'); // 设置收件人信息，如邮件格式说明中的收件人
        $mail->addReplyTo('990198829@qq.com', 'Reply'); // 设置回复人信息，指的是收件人收到邮件后，如果要回复，回复邮件将发送到的邮箱地址
         //$mail->addCC("xxx@163.com");// 设置邮件抄送人，可以只写地址，上述的设置也可以只写地址(这个人也能收到邮件)
         //$mail->addBCC("xxx@163.com");// 设置秘密抄送人(这个人也能收到邮件)
         //$mail->addAttachment("bug0.jpg");// 添加附件

        $mail->Subject = '这是一个测试邮件'; // 邮件标题
        $mail->Body = '邮件内容是 <b>我就是玩玩</b>，哈哈哈！'; // 邮件正文
        //$mail->AltBody = "This is the plain text纯文本";// 这个是设置纯文本方式显示的正文内容，如果不支持Html方式，就会用到这个，基本无用

        if (!$mail->send()) {// 发送邮件
            echo 'Message could not be sent.';
            echo 'Mailer Error: '.$mail->ErrorInfo; // 输出错误信息
        } else {
            echo '发送成功';
        }
    }
}
