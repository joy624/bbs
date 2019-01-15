<?php

namespace app\demo\model;

use think\Model;

class Student extends Model
{
    public function getList()
    {
        return $this->field('id,name,email,mobile,gender,admiss_date')
        ->order('id', 'desc')
        ->select();
    }

    public function getGenderAttr($value)
    {
        $status = [0 => '男', 1 => '女'];

        return $status[$value];
    }

    public function getAdmissDateAttr($value)
    {
        return date('Y-m-d', $value);
    }
}
