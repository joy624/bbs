<?php
namespace app\bbs\service;

use think\Image;
use app\bbs\common\ResponseCode;
use app\bbs\exception\UserException;

class UploadService
{
    // 处理上传图像
    public function thumb($image)
    {
        // 将上传的图像保存到public/static/uploads/目录下
        $filepath = 'static/uploads/';
        if ($info = $image->move($filepath)) {
            // 打开保存到指定位置的图像
            $img = Image::open($filepath.str_replace('\\', '/', $info->getSaveName()));
            // 获取保存到指定static/uploads/目录下，日期目录名和文件名
            $savename =explode('\\', $info->getSaveName());
            // 设置上传图像缩略图保存路径static/uploads/thumb/日期
            $thumb_path=$filepath.'thumb/'.$savename[0];
            // 创建缩略图保存目录
            if (!file_exists($thumb_path)) {
                mkdir($thumb_path, 0777, true);
            }
            // 缩略图大小是150*150，缩略图居中裁剪，并以其上传文件保存的名称存储到缩略图保存的位置
            $img->thumb(150, 150, Image::THUMB_CENTER)
                ->save($thumb_path.'/'.$savename[1]);
            return $thumb_path.'/'.$savename[1];
        } else {
            throw new UserException('上传图像失败', ResponseCode::$USER_MOVE_FILE_FAILED);
        }
    }
}
