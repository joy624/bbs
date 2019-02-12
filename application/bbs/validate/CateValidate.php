<?php

namespace app\bbs\validate;

use think\Validate;

class CateValidate extends Validate
{
    protected $rule = [
        'name' => ['require'],
        'sort' => ['number']
    ];

    protected $message = [
        'name.require' => '名称必须',
        'sort.number' => '排序值为非负整数'
    ];
    protected $scene = [
        'add' => ['name'],
        'edit' => ['name', 'sort']
    ];
}
