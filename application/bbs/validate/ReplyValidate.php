<?php
namespace app\bbs\validate;

use think\Validate;

class ReplyValidate extends Validate
{
    protected $rule = [
        'topic_id' => ['require', 'number'],
        'content' => ['require'],
        'user_id' => ['require', 'number'],
        'id' => ['require', 'number'],
    ];

    protected $message = [
        'topic_id.number' => '主题id必须是非负整数',
        'topic_id.require' => '主题是必须的',
        'content.require' => '回复内容是必须的',
        'user_id.require' => '用户id是必须的',
        'user_id.number' => '用户id必须是非负整数'
    ];
    protected $scene = [
        'add' => ['topic_id', 'user_id', 'content'],
        'edit' => ['id', 'content']
    ];
}
