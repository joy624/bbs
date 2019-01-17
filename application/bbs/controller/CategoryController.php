<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:03
 */
namespace app\bbs\controller;

use think\Controller;
use app\bbs\validate\CateValidate;
use app\bbs\service\CategoryService;
use app\bbs\exception\CategoryException;
use app\bbs\common\ResponseCode;

class CategoryController extends Controller
{
    public function add()
    {
        $name = $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');

        $validate = new CateValidate();
        if (!$validate->scene('add')->check(['name' => $name])) {
            throw new CategoryException('分类名称不能为空',ResponseCode::$CATE_NAME_IS_NULL);
        }

        $cate_service = new CategoryService;
        $cate = $cate_service->addCate($name);
        return  ResponseCode::success($cate);
    }
    public function edit()
    {
        $id = $this->request->post('id', '', 'htmlspecialchars,strip_tags,trim');
        $name = $this->request->post('name', '', 'htmlspecialchars,strip_tags,trim');
        $sort = $this->request->post('sort', '', 'htmlspecialchars,strip_tags,trim');

        $validate = new CateValidate();
        if (!$validate->scene('edit')->check(['name' => $name,'sort' => $sort])) {
            throw new CategoryException('分类名不能为空，排序值必须为非负整数',ResponseCode::$CATE_SORT_ERROR);
        }

        $cate_service = new CategoryService;
        $cate_service->editCate($id,$name,$sort);
        return  ResponseCode::success(true);
    }
    public function delete()
    {
        $id = $this->request->post('id', '', 'htmlspecialchars,strip_tags,trim');

        $cate_service = new CategoryService;
        $cate_service->deleteCate($id);
        return  ResponseCode::success(true);
    }
    public function list()
    {
        $cate_service = new CategoryService;
        $data = $cate_service->listCate();
        return  ResponseCode::success($data);
    }
}