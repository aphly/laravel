<?php
namespace Aphly\Laravel\Libs;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Support\Facades\Storage;

class Image
{
    static protected function _get_extension($url)
    {
        $mimes=array(
            'image/bmp'=>'bmp',
            'image/gif'=>'gif',
            'image/jpeg'=>'jpg',
            'image/png'=>'png',
            'image/x-icon'=>'ico'
        );
        $headers = get_headers($url, 1);
        if(!isset($mimes[$headers['Content-Type']])){
            throw new ApiException(['code'=>10030,'data'=>'','msg'=>'未知类型']);
        }
        return $mimes[$headers['Content-Type']];
    }

    static protected function http_get_data($url) {
        $ch = curl_init ();
        curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        ob_start ();
        curl_exec ( $ch );
        $return_content = ob_get_contents ();
        ob_end_clean ();
        return $return_content;
    }

    static public function avatar($url,$path)
    {
        if($url) {
            $ext = self::_get_extension($url);
            $path = $path.'/'.date('Ym').'/'.date('d').'/'.date('Hi');
            $filename = $path.'/'.TIMESTAMP.mt_rand(1000,9999).'.'.$ext;
            Storage::disk('local')->put($filename, self::http_get_data($url));
            return $filename;
        } else {
            throw new ApiException(['code'=>10031,'data'=>'','msg'=>'url错误']);
        }
    }

}
