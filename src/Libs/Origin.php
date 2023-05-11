<?php
namespace Aphly\Laravel\Libs;

class Origin
{
    static function mkDirBy($path){
        if(is_dir($path)){
            return;
        }
        return is_dir(dirname($path)) || self::mkDirBy(dirname($path))?mkdir($path):false;
    }

    function upload($Dir){
        $imgpath = [];
        if(!empty($_FILES['file']['name'])){
            $file_type = $_FILES['file']['type'];
            foreach ($file_type as $type){
                if(!in_array($type,["image/gif","image/jpeg","image/png"])){
                    return [];
                }
            }
            foreach ($_FILES["file"]['size'] as $size){
                if($size>2000000){
                    return [];
                }
            }
            $file_name = $_FILES['file']['name'];
            $file_tmp_name = $_FILES['file']['tmp_name'];
            $dir = date('Ym').'/'.date('d');
            $path = $Dir.'review/'.$dir;
            $this->mkDirBy($path);
            for($i=0;$i<count($file_name);$i++){
                $aa = pathinfo($file_name[$i]);
                $randname = Func::randStr(18).'.'.$aa['extension'];
                if($file_name[$i] != ''){
                    move_uploaded_file($file_tmp_name[$i], $path.'/'.$randname);
                    $imgpath[] = 'review/'.$dir.'/'.$randname;
                }
            }
        }
        return $imgpath;
    }
}
