<?php
namespace app\bbs\controller;

use think\Controller;
use think\Facade\Request;
use think\Facade\Session;
use app\bbs\service\User as UserService;
use app\bbs\common\ResultCodeMsg;

class Register extends Controller
{
    public function email()
    {
        $name = $this->request->param('name','','htmlspecialchars,strip_tags');
        $password = $this->request->param('password','','htmlspecialchars,strip_tags');
        $email = $this->request->param('email','','htmlspecialchars,strip_tags');
        $code = $this->request->param('code','','htmlspecialchars,strip_tags');

        // 判断邮箱验证码是否正确
        if( session('emailCode') !== (int)$code) {
          return ResultCodeMsg::$CAPTCHA_ERROR;
        }

        $salt = get_salt(5);
        // 向数据表中添加数据
         $data = UserService::create([
            'name'  =>  $name,
            'email' =>  $email,
            'password'=> md5(md5($password).$salt),
             'salt'=>$salt
        ], ['name','email','password','salt'], true);
        if($data){
            ResultCodeMsg::$SUCCESS['data'] = null;
            return json( ResultCodeMsg::$SUCCESS);
        }else{
            ResultCodeMsg::$REGISTER_ERROR['data'] = null;
            return json( ResultCodeMsg::$REGISTER_ERROR);
        }
    }
    // 向邮箱发送验证码
    public function sendEmail($email,$name){
        $subject='用户注册验证码';
        $code = mt_rand(100000,999999);
        $body='您的验证码是：'.$code;
        $res=send_mail($email,$name,$subject,$body);
        if($res){
            session('emailCode',$code);// 记录邮件验证码
            return ResultCodeMsg::$SUCCESS;
        }else{
            return ResultCodeMsg::$SEND_ERROR;
        }
    }
}