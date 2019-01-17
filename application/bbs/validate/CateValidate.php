<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/16
 * Time: 上午12:21
 */

namespace app\bbs\validate;

use think\Validate;

class CateValidate extends Validate
{
    protected $rule = [
        'name'  =>  ['require'],
        'sort'  =>  ['number'],
    ];

    protected $message  =   [
        'name.require' => '名称必须',
        'sort.number'  => '排序值为非负整数'
    ];
    protected $scene = [
        'add' => ['name'],
        'edit' => ['name', 'sort']
    ];
}