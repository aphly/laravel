<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class UploadFile extends Model
{
    use HasFactory;
    protected $table = 'admin_upload_file';
    //public $timestamps = false;

    protected $fillable = [
        'uuid','level_id','path','file_type','file_size','remote'
    ];

    public $size; //M

    public $allow_ext;

    static public $remote_disk = 'oss';

    public function __construct($size=0.5,$allow_ext=['png','jpg','jpeg','gif','webp'])
    {
        $this->size = $size;
        $this->allow_ext = $allow_ext;
        parent::__construct();
    }

    static function getPath($file_path,$remote=0,$is_img=true){
        if($file_path){
            $disk = $remote?self::$remote_disk:'local';
            return Storage::disk($disk)->url($file_path);
        }else{
            return $is_img?URL::asset('static/base/img/none.png'):null;
        }
    }

    function isRemote(){
        return trim(env('FILESYSTEM_DISK'))===self::$remote_disk?1:0;;
    }

    function upload($file,$path,$disk=false){
        $arr = $this->_upload($file,$path);
        if($disk){
            return $arr[0]->store($arr[1],$disk);
        }else{
            return $arr[0]->store($arr[1]);
        }
    }

    static function disk($remote=0){
        if($remote===1){
            return self::$remote_disk;
        }
        return 'local';
    }

    static function del($path,$remote=0){
        if($path){
            $disk = $remote?self::$remote_disk:'local';
            Storage::disk($disk)->delete($path);
        }
    }

    function uploadSaveDb($file,$path,$disk=false){
        $arr = $this->_upload($file,$path);
        if($disk){
            $input['remote'] = $disk===self::$remote_disk?1:0;
            $input['path'] = $arr[0]->store($arr[1],$disk);
        }else{
            $input['remote'] = trim(env('FILESYSTEM_DISK'))===self::$remote_disk?1:0;
            $input['path'] = $arr[0]->store($arr[1]);
        }
        $input['uuid'] = Manager::user()->uuid;
        $input['level_id'] = Manager::user()->level_id;
        $input['file_type'] = $arr[2];
        $input['file_size'] = $arr[3];
        return self::create($input);
    }

    function uploads($limit,$files,$path,$disk=false){
        if($limit && count($files)>$limit){
            throw new ApiException(['code'=>704,'msg'=>'Limit of '.$limit.' files']);
        }
        $res = $check = [];
        foreach ($files as $file){
            $check[] = $this->_upload($file,$path);
        }
        foreach ($check as $file){
            if($disk) {
                $res[] = $file[0]->store($file[1],$disk);
            }else{
                $res[] = $file[0]->store($file[1]);
            }
        }
        return $res;
    }

    function _upload($file,$path){
        if ($file) {
            if($file->isValid()){
                $ext = $file->extension();
                $size = $file->getSize();
                if($size/1024/1024 > $this->size){
                    throw new ApiException(['code'=>701,'msg'=>'Size over '.$this->size.' M']);
                }
                if(!in_array($ext,$this->allow_ext)){
                    throw new ApiException(['code'=>700,'msg'=>'Format not supported']);
                }
                return [$file,$path.'/'.date('Ym').'/'.date('d').'/'.date('Hi'),$ext,$size];
            }else{
                throw new ApiException(['code'=>702,'msg'=>'Upload error']);
            }
        }else{
            throw new ApiException(['code'=>703,'msg'=>'Upload error']);
        }
    }

    static function formatSize($size){
        if ($size >= 1073741824){
            $size = round($size / 1073741824 * 100) / 100 . ' GB';
        }elseif ($size >= 1048576){
            $size = round($size / 1048576 * 100) / 100 . ' MB';
        }elseif ($size >= 1024){
            $size = round($size / 1024 * 100) / 100 . ' KB';
        }else{
            $size = $size . ' Bytes';
        }
        return $size;
    }

}
