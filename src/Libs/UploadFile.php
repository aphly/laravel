<?php
namespace Aphly\Laravel\Libs;

use Aphly\Laravel\Exceptions\ApiException;

class UploadFile
{
//    static function filePath($file){
//        $cache = Setting::getCache();
//        if(!$cache){
//            return '/img/avatar.png';
//        }
//        if($file){
//            $host = $cache['oss_status'] ? $cache['oss_host'] : $cache['siteurl'];
//            return $host.'/uploads/'.$file;
//        }else{
//            return $cache['siteurl'].'/img/avatar.png';
//        }
//    }

    static function upload($file,$path){
        if ($file->isValid()) {
            $ext = $file->extension();
            $size = $file->getSize();
            if($size/1024/1024 > 5){
                throw new ApiException(['code'=>701,'data'=>'','msg'=>'图片大小超过 5M']);
            }
            $allow_ext= ['png','jpg','jpeg','gif'];
            if(!in_array($ext,$allow_ext)){
                throw new ApiException(['code'=>700,'data'=>'','msg'=>'格式不支持']);
            }
            $path = $path.'/'.date('Ym').'/'.date('d').'/'.date('Hi');
            $res = $file->store($path);
            if($res){
                return $res;
            }else{
                throw new ApiException(['code'=>702,'data'=>'','msg'=>'上传失败']);
            }
        }else{
            throw new ApiException(['code'=>703,'data'=>'','msg'=>'上传错误']);
        }
    }

}
