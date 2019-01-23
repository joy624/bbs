<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/17
 * Time: 上午12:05
 */

namespace app\bbs\validate;

use think\Validate;

class ResetPasswordValidate extends Validate
{
    protected $rule = [
        'id'  =>  ['require', 'integer'],
        'old_password' =>  ['require','min'=>6, 'max' => 18, 'regex' => '/^[a-zA-Z0-9]\w+/'],
        'new_password' => ['require','min'=>6, 'max' => 18, 'regex' => '/^[a-zA-Z0-9]\w+/']
    ];
}
