<?php

namespace app\test\model;

use think\Model;

class Cate extends Model
{
    public function catetree()
    {
        $cateres = $this->order('sort desc')->select();

        return $this->sort($cateres);
    }

    public function sort($data, $pid = 0, $level = 0)
    {
        static $arr = [];
        foreach ($data as $k => $v) {
        }
    }
}
