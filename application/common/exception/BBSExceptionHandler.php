<?php

namespace app\common\exception;

use Exception;
use think\exception\Handle;

class BBSExceptionHandler extends Handle
{
    public function render(Exception $e)
    {
        // 自定义异常，指定异常返回格式
        if ($e instanceof BBSException) {
            return json(['code' => $e->getCode(), 'msg' => $e->getMessage(),'data' => null]);
        }
        // 其他错误交给系统处理
        return parent::render($e);
    }
}
