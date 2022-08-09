<?php
namespace Aphly\Laravel\Libs;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Support\Facades\Storage;

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
    public $size; //kb
    public $limit;

    public function __construct($size=500,$limit=5)
    {
        $this->size = $size;
        $this->limit = $limit;
    }

    function img($file,$path){
        $arr = $this->_img($file,$path);
        return $arr[0]->store($arr[1]);
    }

    function imgs($files,$path,$limit=0){
        $files_obj = $res = [];
        if($limit){
            if(count($files)>$limit){
                throw new ApiException(['code'=>704,'data'=>'','msg'=>'Limit of '.$limit.' images']);
            }else{
                $files = array_slice($files,0,$limit);
            }
        }
        foreach ($files as $file){
            $files_obj[] = $this->_img($file,$path);
        }
        foreach ($files_obj as $val){
            $res[] = $val[0]->store($val[1]);
        }
        return $res;
    }

     function _img($file,$path){
        if ($file && $file->isValid()) {
            $ext = $file->extension();
            $size = $file->getSize();
            if($size/1024 > $this->size){
                throw new ApiException(['code'=>701,'data'=>'','msg'=>'Image size over '.$this->size.' KB']);
            }
            $allow_ext= ['png','jpg','jpeg','gif'];
            if(!in_array($ext,$allow_ext)){
                throw new ApiException(['code'=>700,'data'=>'','msg'=>'Format not supported']);
            }
            return [$file,$path.'/'.date('Ym').'/'.date('d').'/'.date('Hi')];
        }else{
            throw new ApiException(['code'=>703,'data'=>'','msg'=>'Upload error']);
        }
    }

}
