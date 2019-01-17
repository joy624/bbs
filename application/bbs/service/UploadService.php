<?php
/**
 * Created by PhpStorm.
 * User: qiaozhiming
 * Date: 2019/1/15
 * Time: 下午11:25
 */

namespace app\bbs\service;

use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;
use app\bbs\model\UserModel;
use think\Image;

class UploadService
{
    // 处理上传图像
    public function thumb($image)
    {
        // 将上传的图像保存到public/static/uploads/目录下
        $filepath = 'static/uploads/';
        if($info = $image->move($filepath)){
            $img = Image::open($filepath.str_replace('\\','/',$info->getSaveName()));
            $savename =explode('\\',$info->getSaveName());
            $thumb_path=$filepath.'thumb/'.$savename[0];
            if(!file_exists($thumb_path)){
                mkdir($thumb_path,0777,true);
            }
           $img->thumb(150, 150, Image::THUMB_CENTER)->save($thumb_path.'/'.$savename[1]);
           return $thumb_path.'/'.$savename[1];
        }else{
            throw new UserException('上传图像失败',ResponseCode::$UPLOAD_ERROR);
        }
    }
}