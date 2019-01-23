<?php
namespace app\bbs\service;

use app\bbs\model\UserModel;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class UserService
{
    // 注册用户
    public function addUser($name, $password, $email)
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
        if (!$user->save(['name'  =>  $name, 'password'=> $password, 'email' =>  $email, 'salt'=>$salt])) {
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
        if (!$user->save(['is_active'  => $flag,], ['id' => $id])) {
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
            'salt'  => $salt,
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
            'img_url'  => $thumb_path
        ], ['id' => $id]);
        if (!$res) {
            throw new UserException('上传头像失败', ResponseCode::$USER_SAVE_IMG_FAILED);
        }
    }


    // 修改用户邮箱
    public function modifyEmail($id, $email)
    {
        if (UserModel::whereEmail('=', $email)->find()) {
            throw new UserException('已存在，请重新设置', ResponseCode::$USER_NOT_EXIST);
        }
        $user = new UserModel;
        $res = $user->save([
            'email'  => $email,
            'is_active'=>0
        ], ['id' => $id]);
        if (!$res) {
            throw new UserException('修改错误', ResponseCode::$EDIT_ERROR);
        }
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
        if (! $user->save(['name'  => $name], ['id' => $id])) {
            throw new UserException('修改错误', ResponseCode::$USER_EDIT_ERROR);
        }
    }
}
