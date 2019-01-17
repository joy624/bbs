<?php
namespace app\bbs\controller;

use app\bbs\exception\UserException;
use app\bbs\service\EmailService;
use app\bbs\service\SecureService;
use app\bbs\validate\ResetPasswordValidate;
use think\Controller;
use app\bbs\validate\UserValidate;
use app\bbs\common\ResponseCode;
use app\bbs\service\UserService;
use app\bbs\service\UploadService;
use think\facade\Cache;

class UserController extends Controller
{
    // 用户注册
    public function register()
    {

        $params['name'] = $this->request->post('name');
        $params['password'] = $this->request->post('password');
        $params['email'] = $this->request->post('email');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('register')->check($params)) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 添加用户
        $user_service = new UserService();
        $user = $user_service->add(
            $params['name'],
            $params['password'],
            $params['email']
        );
        return ResponseCode::success($user);
    }

    // 重置密码
    public function resetPassword()
    {
        // 接收并过滤重置密码的用户id、旧密码、新密码

        $id = $this->request->post('id');
        $old_password = $this->request->post('old_password');
        $new_password = $this->request->post('new_password');

        $validate = new ResetPasswordValidate();
        if (!$validate->check(['id' => $id, 'old_password' => $old_password, 'new_password' => $new_password])) {
            throw new UserException($validate->getError(), ResponseCode::$RESETPASSWORD_ERROR);
        }

        // 验证对应用户的密码是否正确
        $user_service = new UserService();
        $user_service->verifyPassword($id, $old_password);

        // 重置用户密码
        $user_service->updatePassword($id, $new_password);
        return ResponseCode::success(true);
    }

    // 忘记密码
    public function findPassword()
    {
        $email = $this->request->post('email');

        // 利用UserValidate验证器验证用户名、密码和邮箱是否符合指定的规范
        $validate = new UserValidate();
        if (!$validate->scene('editEmail')->check(['email'=>$email])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 根据邮箱获取用户名
        $user_service = new UserService();
        $user = $user_service->getUserByEmail($email);
        if(!$user){
            throw new UserException('邮箱未注册', ResponseCode::$USER_NOT_EXIST);
        }

        $subject = '找回密码邮件';
        // 设置账户激活码
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();

        $body = "亲爱的".$user->name."：<br />请点击下面的链接来重置您的密码。<br />
http://bbs.com/bbs/user/updatePwd?key=".$key."<br />如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。<br />
该验证邮件有效期为30分钟，超时请重新发送邮件。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, $user->name, $subject, $body, $key);

       if(!Cache::set('validate_email_url_'.$key, $user->id, 30*60)){
           throw new SystemException('找回密码失败，请重试或联系管理员',ResponseCode::$FIND_PASSWORD_ERROR);
       }

    }

    //
    public function updatePwd()
    {
        if($this->request->isPost()){
            $id = $this->request->post('id');
            $password = $this->request->post('password');

            // 利用UserValidate验证器验证密码是否符合指定的规范
            $validate = new UserValidate();
            if (!$validate->scene('updatePwd')->check(['password'=>$password])) {
                throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
            }

            $user_service = new UserService();
            // 重置用户密码
            $user_service->updatePassword($id, $password);
            return ResponseCode::success(true);

        }else{
            $key = $this->request->get('key');
            $id = Cache::get('validate_email_url_'.$key);
            if(!$id){
                throw new UserException('验证信息已过期或非法输入，请重新找回密码',ResponseCode::$EMAIL_CODE_ERROR);
            }
            return ResponseCode::success($id);//todo html
        }
    }

    // 上传头像
    public function headPortrait()
    {
        $id = $this->request->post('id');
        $portrait = $this->request->file('portrait');
        if (true !== $this->validate(['image'=>$portrait], ['image'=>'require|image'])) {
            throw new UserException('请选择图像上传', ResponseCode::$FILE_NOT_FOUND);
        }

        // 将上传图像生成缩略图，并保存到指定位置
        $upload_service = new UploadService();
        $thumb_path = $upload_service->thumb($portrait);

        // 将缩略图路径保存到数据表中
        $user_service = new UserService();
        $user_service->saveThumb($id, $thumb_path);
        return ResponseCode::success($thumb_path);
    }

    // 修改用户
    public function editName()
    {
        $id = $this->request->post('id');
        $name = $this->request->post('name');

        $res = $this->validate(['name' => $name],'app\bbs\validate\UserValidate.editName');
        if (true !== $res) {
            throw new UserException($res, ResponseCode::$USER_NOT_STANDARD);
        }

        // 修改用户信息
        $user_service = new UserService();
        $user = $user_service->modifyName($id, $name);
        return ResponseCode::success($user);
    }

//    // 修改用户邮箱
//    public function editEmail()
//    {
//
//        $id = $this->request->post('id');
//        $email = $this->request->post('email');
//
//        $res = $this->validate(['email' => $email],'app\bbs\validate\UserValidate.editEmail');
//        if (true !== $res) {
//            throw new UserException($res, ResponseCode::$USER_NOT_STANDARD);
//        }
//
//        // 修改用户信息
//        $user_service = new UserService();
//        $user_service->modifyEmail($id, $email);
//        return ResponseCode::success(true);
//    }
}
