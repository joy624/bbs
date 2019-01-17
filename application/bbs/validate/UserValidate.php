<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/16
 * Time: 上午12:21
 */

namespace app\bbs\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'name'  =>  ['require','min'=>5, 'max' => 25, 'regex' => '/^[a-zA-Z]\w+/'],
        'email' =>  'email',
        'password' => ['require','min'=>6, 'max' => 18, 'regex' => '/^[a-zA-Z0-9]\w+/']
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'name.min'     => '名称最少不能少于5个字符',
        'name.max'     => '名称最多不能超过25个字符',
        'name.regex'   => '名称以英文字母开头，由字母、数字和_组成',
        'email'        => '邮箱格式错误',
        'password.require'     => '密码必须',
        'password.min'     => '密码最少不能少于6个字符',
        'password.max'     => '密码最多不能超过18个字符',
        'password.regex'   => '密码可以英文字母和数字开头，由字母、数字和_组成'
    ];

    protected $scene = [
        'register' => ['name', 'email', 'password'],
        'login' => ['name', 'password'],
        'editEmail'=> ['email'],
        'editName'=>['name']
    ];
}