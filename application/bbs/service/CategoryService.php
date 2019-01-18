<?php
/**
 * Created by PhpStorm.
 * User: qzm
 * Date: 2019-1-17
 * Time: 15:31
 */
namespace app\bbs\service;

use app\bbs\model\CategoryModel;
use app\bbs\exception\CategoryException;
use app\bbs\common\ResponseCode;

class CategoryService
{
    public function addCate($name){
        if(CategoryModel::whereName('=',$name)->find()){
           throw new CategoryException('分类名已经存在，请重新设置',ResponseCode::$CATE_NAME_IS_EXIST);
        }
        $cate = CategoryModel::create(['name'=>$name],['name']);
        if(!$cate){
            throw new CategoryException('添加分类失败',ResponseCode::$CATE_FAILD);
        }
        return $cate;
    }

    public function  editCate($id,$name,$sort)
    {
        $cate = CategoryModel::get($id);
        if(!$cate){
            throw new CategoryException('分类不存在',ResponseCode::$CATE_NOT_EXIST);
        }
        if(CategoryModel::whereName('=',$name)->where('id','<>',$id)->find()){
            throw new CategoryException('分类名已经存在，请重新设置',ResponseCode::$CATE_NAME_IS_EXIST);
        }
        if($sort === ""){
            $sort = $cate->sort;
        }
        $cate = new CategoryModel;
        if(!$cate->save(['name'=>$name, 'sort'=>$sort, 'update_time'=>date('Y-m-d H:i:s',time())],['id'=>$id])){
            throw new CategoryException('修改分类失败',ResponseCode::$CATE_FAILD);
        }
    }
    public function deleteCate($id)
    {
        if(!CategoryModel::get($id)){
            throw new CategoryException('分类不存在',ResponseCode::$CATE_NOT_EXIST);
        }
        if(!CategoryModel::destroy($id)){
            throw new CategoryException('修改分类失败',ResponseCode::$CATE_FAILD);
        }
    }
    public function listCate()
    {
        return CategoryModel::field('id, name, sort')->order('id','ASC')->select();
    }
}