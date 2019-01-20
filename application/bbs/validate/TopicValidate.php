<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/16
 * Time: 上午12:21
 */

namespace app\bbs\validate;

use think\Validate;

class TopicValidate extends Validate
{
    protected $rule = [
        'id' => ['require', 'number'],
        'title'         =>  ['require'],
        'category_id'   =>  ['require', 'number'],
        'user_id'       =>  ['require', 'number'],
        'content'       =>  ['require']
    ];

    protected $message  =   [
        'title.require' => '标题是必须的',
        'category_id.require'  => '分类是必须的',
        'category_id.number'  => '分类id必须是非负整数',
        'user_id.require'  => '用户是必须的ccccc',
        'user_id.number'  => '用户id必须是非负整数',
        'content.require' => '内容是必须的'
    ];
    protected $scene = [
        'add'=>['title', 'category_id', 'user_id', 'content'],
        'edit' => ['id', 'title', 'category_id', 'user_id', 'content']
    ];
}