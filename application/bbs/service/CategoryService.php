<?php

namespace app\bbs\service;

use app\bbs\model\CategoryModel;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;
use app\bbs\model\TopicModel;
use app\bbs\common\Constants;

class CategoryService
{
    public function addCate($name)
    {
        if (CategoryModel::whereName('=', $name)->find()) {
            throw new UserException('分类名已经存在，请重新设置', ResponseCode::$CATE_NAME_IS_EXIST);
        }
        $cate = CategoryModel::create(['name' => $name], ['name']);
        if (!$cate) {
            throw new UserException('添加分类失败', ResponseCode::$CATE_FAILED);
        }
        return $cate;
    }

    public function editCate($id, $name, $sort)
    {
        $cate = CategoryModel::get($id);
        if (!$cate) {
            throw new UserException('分类不存在', ResponseCode::$CATE_NOT_EXIST);
        }
        if (CategoryModel::whereName('=', $name)->where('id', '<>', $id)->find()) {
            throw new UserException('分类名已经存在，请重新设置', ResponseCode::$CATE_NAME_IS_EXIST);
        }

        // 判断sort排序是否为空
        if ($sort === "" or $sort === null) {
            $sort = $cate->sort;
        }
        $cate = new CategoryModel;
        if (!$cate->save(['name' => $name, 'sort' => $sort, 'update_time' => date('Y-m-d H:i:s', time())], ['id' => $id])) {
            throw new UserException('修改分类失败', ResponseCode::$CATE_FAILED);
        }
    }

    public function deleteCate($id)
    {
        if (!CategoryModel::get($id)) {
            throw new UserException('分类不存在', ResponseCode::$CATE_NOT_EXIST);
        }
        if (!CategoryModel::destroy($id)) {
            throw new UserException('删除分类失败', ResponseCode::$CATE_FAILED);
        }
        // 将该分类对应的topic的is_show设置为0，实现删除分类时，软删除话题
        $topic_model = new TopicModel();
        if (!$topic_model->save(['is_show' => Constants::IS_NOT_SHOW], ['category_id' => $id])) {
          throw new UserException('删除主题失败', ResponseCode::$TOPIC_DELETE_FAILED);
        }

    }

    public function listCate()
    {
        return CategoryModel::field('id, name, sort')->order('sort', 'DESC')->select();
    }
}
