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
    public $size; //M

    public $limit;

    public $allow_ext;

    public function __construct($size=0.5,$limit=0,$allow_ext=['png','jpg','jpeg','gif','webp'])
    {
        $this->size = $size;
        $this->limit = $limit;
        $this->allow_ext = $allow_ext;
    }

    function upload($file,$path){
        $arr = $this->_upload($file,$path);
        return $arr[0]->store($arr[1]);
    }

    function uploads($files,$path){
        if($this->limit && count($files)>$this->limit){
            throw new ApiException(['code'=>704,'msg'=>'Limit of '.$this->limit.' files']);
        }
        $res = $check = [];
        foreach ($files as $file){
            $check[] = $this->_upload($file,$path);
        }
        foreach ($check as $file){
            $res[] = $file[0]->store($file[1]);
        }
        return $res;
    }

    function _upload($file,$path){
        if ($file && $file->isValid()) {
            $ext = $file->extension();
            $size = $file->getSize();
            if($size/1024/1024 > $this->size){
                throw new ApiException(['code'=>701,'msg'=>'Size over '.$this->size.' M']);
            }
            if(!in_array($ext,$this->allow_ext)){
                throw new ApiException(['code'=>700,'msg'=>'Format not supported']);
            }
            return [$file,$path.'/'.date('Ym').'/'.date('d').'/'.date('Hi')];
        }else{
            throw new ApiException(['code'=>703,'msg'=>'Upload error']);
        }
    }

}
