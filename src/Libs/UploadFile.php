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

    function upload($file,$path){
        if ($file->isValid()) {
            $ext = $file->extension();
            $allow_ext= ['png','jpg','jpeg'];
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
