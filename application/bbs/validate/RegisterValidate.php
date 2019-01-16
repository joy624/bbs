<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/16
 * Time: 上午12:21
 */

namespace app\bbs\validate;

use think\Validate;

class RegisterValidate extends Validate
{
    protected $rule = [
        'name'  =>  'require|max:10',
        'email' =>  'email',
        'password' => 'require'
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'name.max'     => '名称最多不能超过10个字符',
        'email'        => '邮箱格式错误',
        'password'     => '密码必须'
    ];

}