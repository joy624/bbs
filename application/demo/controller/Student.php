<?php

namespace app\demo\controller;

use think\Controller;
use app\demo\model\Student as StudentModel;

class Student extends Controller
{
    public function index()
    {
        $model = new StudentModel();
        $data = $model->getList();
        var_dump($data);
        die;
        $this->assign('data', $data);

        return $this->fetch();
    }
}
