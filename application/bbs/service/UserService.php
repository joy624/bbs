<?php

namespace app\bbs\service;

use app\bbs\model\UserModel;
use think\facade\Cache;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;
use app\bbs\exception\SystemException;

class UserService
{
    // 注册用户
    public function addUser($name, $password, $email, $img_url = '/static/default-photo.jpeg')
    {
        // 判断注册的用户名是否已经存在
        if ($this->getUserByName($name)) {
            throw new UserException('此用户名已经存在,请重新设置', ResponseCode::$USER_NAME_EXIST);
        }
        if ($this->getUserByEmail($email)) {
            throw new UserException('此邮箱已经存在,请重新设置', ResponseCode::$USER_EMAIL_EXIST);
        }

        // 加密salt和密码
        $secure_service = new SecureService();
        $salt = $secure_service->genRandomString();
        $password = md5(md5($password) . $salt);

        $user = new UserModel();
        if (!$user->save(['name' => $name,
            'password' => $password,
            'email' => $email,
            'salt' => $salt,
            'img_url' => $img_url])) {
            throw new UserException('注册用户出错', ResponseCode::$USER_REGISTER_FAILED);
        }

        return UserModel::field(UserModel::getSafeAttrs())->get($user->id);
    }

    // 通过用户名获取用户信息
    public function getUserByName($name)
    {
        return UserModel::field(UserModel::getSafeAttrs())->where('name', $name)->find();
    }

    // 通过邮箱获取用户信息
    public function getUserByEmail($email)
    {
        return UserModel::field(UserModel::getSafeAttrs())->where('email', $email)->find();
    }

    // 编辑用户的激活码，1表示激活，0表示未激活
    public function editActiveFlag($id, $flag)
    {
        $user = new UserModel();
        if (!$user->save(['is_active' => $flag], ['id' => $id])) {
            throw new UserException('激活账户失败', ResponseCode::$USER_ACTIVATE_FAILED);
        }
        return UserModel::field(UserModel::getSafeAttrs())->get($id);
    }

    // 验证指定用户的密码是否正确
    public function verifyPassword($id, $old_password)
    {
        $user = UserModel::field('password, salt')->where('id', $id)->find();
        if (!$user) {
            throw new UserException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }
        if ($user->password !== md5(md5($old_password) . $user->salt)) {
            throw new UserException('密码输入错误', ResponseCode::$USER_PASSWORD_ERROR);
        }
    }

    // 重置用户密码
    public function updatePassword($id, $new_password)
    {
        $secure_service = new SecureService();
        $salt = $secure_service->genRandomString();
        $password = md5(md5($new_password) . $salt);
        $user = new UserModel;
        $res = $user->save([
            'salt' => $salt,
            'password' => $password
        ], ['id' => $id]);
        if (!$res) {
            throw new UserException('重置密码失败', ResponseCode::$USER_RESETPASSWORD_FAILED);
        }
    }

    // 保存用户头像地址
    public function saveThumb($id, $thumb_path)
    {
        if (!UserModel::get($id)) {
            throw new UserException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel();
        $res = $user->save([
            'img_url' => $thumb_path
        ], ['id' => $id]);
        if (!$res) {
            throw new UserException('上传头像失败', ResponseCode::$USER_SAVE_IMG_FAILED);
        }
    }


    // 修改用户邮箱
    public function modifyEmail($id, $email)
    {
        $user = new UserModel;
        $res = $user->save([
            'email' => $email
        ], ['id' => $id]);
        if (!$res) {
            return false;
        }
        return true;
    }

    // 修改用户名
    public function modifyName($id, $name)
    {
        if (!UserModel::get($id)) {
            throw new UserException('用户不存在', ResponseCode::$USER_NOT_EXIST);
        }
        if ($this->getUserByName($name)) {
            throw new UserException('此用户名已经存在,请重新设置', ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel;
        if (!$user->save(['name' => $name], ['id' => $id])) {
            throw new UserException('修改错误', ResponseCode::$USER_EDIT_ERROR);
        }
    }

    public function sendEmailForFindingPass($email)
    {
        $user = $this->getUserByEmail($email);
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

        if (!Cache::set('update_email_id_' . $key, $id, 30 * 60) ||
          !Cache::set('update_email_' . $key, $email, 30 * 60)) {
            throw new SystemException('发送修改链接失败，请重试或联系管理员', ResponseCode::$EMAIL_SEND_FAILED);
        }
    }
}
