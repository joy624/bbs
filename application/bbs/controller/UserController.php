<?php
namespace app\bbs\controller;

use think\Controller;
use think\facade\Cache;
use app\bbs\service\UserService;
use app\bbs\service\SecureService;
use app\bbs\service\EmailService;
use app\bbs\service\UploadService;
use app\bbs\validate\UserValidate;
use app\bbs\validate\ResetPasswordValidate;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

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
        $user = $user_service->addUser(
            $params['name'],
            $params['password'],
            $params['email']
        );

        // 注册用户后，向用户的邮箱中发送激活链接
        $subject = '用户帐号激活';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的".$params['name']."：<br/>感谢您在我站注册了新帐号。<br/>请点击链接激活您的帐号。<br/> 
    <a href='http://my.test.tp/bbs/user/activateAccount?key=".$key."' target='_blank'> http://my.test.tp/bbs/user/activateAccount?key=".$key."</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($params['email'], $params['name'], $subject, $body);

        if (!Cache::set('activate_email_url_'.$key, $user->id, 24*60*60)) {
            throw new SystemException('账户激活发送失败，请重试或联系管理员', ResponseCode::$EMAIL_ACTIVATE_KEY_SAVE_FAILED);
        }

        return ResponseCode::success($user);
    }

    // 激活账户
    public function activateAccount()
    {
        $key = $this->request->get('key');
        $id = Cache::get('activate_email_url_'.$key);
        if (!$id) {
            throw new UserException('激活链接已过期或非法输入，请重新激活账户', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
        }
        $flag = 1;
        $user_service = new UserService();
        $user = $user_service->editActiveFlag($id, $flag);
        return ResponseCode::success($user);
    }

    // 根据老密码重置密码
    public function resetPassword()
    {
        // 接收并过滤重置密码的用户id、旧密码、新密码
        $id = $this->request->post('id');
        $old_password = $this->request->post('old_password');
        $new_password = $this->request->post('new_password');

        $validate = new ResetPasswordValidate();
        if (!$validate->check(['id' => $id, 'old_password' => $old_password, 'new_password' => $new_password])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_RESETPASSWORD_NOT_STANDARD);
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
        if (!$user) {
            throw new UserException('邮箱未注册', ResponseCode::$USER_NOT_EXIST);
        }

        $subject = '找回密码邮件';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的".$user->name."：<br />请点击下面的链接来重置您的密码。<br />
 <a href='http://my.test.tp/bbs/user/updatePwd?key=".$key."' target='_blank'>http://my.test.tp/bbs/user/updatePwd?key=".$key."</a><br/> 
如果您的邮箱不支持链接点击，请将以上链接地址拷贝到你的浏览器地址栏中。<br />该验证邮件有效期为30分钟，超时请重新发送邮件。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, $user->name, $subject, $body);

        if (!Cache::set('validate_email_url_'.$key, $user->id, 30*60)) {
            throw new SystemException('找回密码失败，请重试或联系管理员', ResponseCode::$FIND_PASSWORD_FAILED);
        }
    }

    // 根据邮件链接更新密码
    public function updatePwd()
    {
        // get请求，获取邮箱码，重置密码；post请求，重置用户密码
        if ($this->request->isPost()) {
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
        } else {
            $key = $this->request->get('key');
            $id = Cache::get('validate_email_url_'.$key);
            if (!$id) {
                throw new UserException('验证信息已过期或非法输入，请重新找回密码', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
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
            throw new UserException('请选择图像上传', ResponseCode::$USER_NOT_STANDARD);
        }

        // 将上传图像生成缩略图，并保存到指定位置
        $upload_service = new UploadService();
        $thumb_path = $upload_service->thumb($portrait);

        // 将缩略图路径保存到数据表中
        $user_service = new UserService();
        $user_service->saveThumb($id, $thumb_path);
        return ResponseCode::success($thumb_path);
    }

    // 修改用户名
    public function editName()
    {
        $id = $this->request->post('id');
        $name = $this->request->post('name');

        // 利用UserValidate验证器验证用户名
        $validate = new UserValidate();
        if (!$validate->scene('editName')->check(['name'=>$name])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 修改用户信息
        $user_service = new UserService();
        $user_service->modifyName($id, $name);
        return ResponseCode::success(true);
    }

    // 向用户邮箱发送链接修改邮箱
    public function editEmail()
    {
        $id = $this->request->post('id');
        // 此邮箱是用户修改前的邮箱
        $email = $this->request->post('email');

        // 利用UserValidate验证器验证邮箱
        $validate = new UserValidate();
        if (!$validate->scene('editEmail')->check(['email' => $email])) {
            throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
        }

        // 向用户的邮箱中发送验证链接
        $subject = '修改用户邮箱链接';
        $secure_service = new SecureService();
        $key = $secure_service->genRandomString();
        $body = "亲爱的用户：<br/>若想要修改邮箱，请点击以下链接。<br/> 
    <a href='http://my.test.tp/bbs/user/updateEmail?key=".$key."' target='_blank'> http://my.test.tp/bbs/user/updateEmail?key=".$key."</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接30分钟内有效。";

        $email_service = new EmailService();
        $email_service->sendEmailURL($email, '', $subject, $body);

        if (!Cache::set('update_email_url_'.$key, $id, 30*60)) {
            throw new SystemException('发送修改链接失败，请重试或联系管理员', ResponseCode::$EMAIL_SEND_FAILED);
        }
    }
    // 验证用户链接，修改用户邮箱并发送激活链接
    public function updateEmail()
    {
        if ($this->request->isPost()) {
            $id = $this->request->post('id');
            $email = $this->request->post('email');

            // 利用UserValidate验证器验证邮箱
            $validate = new UserValidate();
            if (!$validate->scene('editEmail')->check(['email' => $email])) {
                throw new UserException($validate->getError(), ResponseCode::$USER_NOT_STANDARD);
            }

            // 重置用户邮箱
            $user_service = new UserService();
            $user_service->modifyEmail($id, $email);

            // 重置邮箱后激活账户
            $subject = '用户帐号激活';
            $secure_service = new SecureService();
            $key = $secure_service->genRandomString();
            $body = "亲爱的用户：<br/>请点击链接激活您的帐号。<br/> 
    <a href='http://my.test.tp/bbs/user/activateAccount?key=".$key."' target='_blank'> http://my.test.tp/bbs/user/activateAccount?key=".$key."</a><br/> 
    如果以上链接无法点击，请将它复制到你的浏览器地址栏中进入访问，该链接24小时内有效。";

            $email_service = new EmailService();
            $email_service->sendEmailURL($email, '', $subject, $body);

            if (!Cache::set('activate_email_url_'.$key, $id, 24*60*60)) {
                throw new SystemException('账户激活发送失败，请重试或联系管理员', ResponseCode::$EMAIL_ACTIVATE_KEY_SAVE_FAILED);
            }

            return ResponseCode::success(true);
        } else {
            $key = $this->request->get('key');
            $id = Cache::get('update_email_url_'.$key);
            if (!$id) {
                throw new UserException('验证信息已过期或非法输入，请重新激活账户', ResponseCode::$USER_ACTIVATE_KEY_ERROR);
            }
            return ResponseCode::success($id);//todo html
        }
    }
}
