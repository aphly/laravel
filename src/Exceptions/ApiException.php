<?php
namespace Aphly\Laravel\Exceptions;

use Illuminate\Http\Request;

class ApiException extends \Exception
{
    private $data = [];
    /**
     * @var mixed|string
     */
    public $msg;
    public $code;

    private $arr;

    private $string;

    private $cookie;
    /**
     * BusinessException constructor.
     *
     * @param array|string $arr
     */
    public function __construct($arr,$cookie=false)
    {
        parent::__construct();
        $this->cookie = $cookie;
        if(is_array($arr)){
            $this->code = $arr['code'] ?? 0;
            $this->msg  = $arr['msg'] ?? '';
            $this->data = $arr['data'] ??'';
            $this->arr = true;
        }else{
            $this->arr = false;
            $this->string = $arr;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function render(Request $request)
    {
        if($request->expectsJson()) {
        }
       // $this->cookie = cookie('name', 'value', $minutes);
        if($this->cookie){
            if($this->arr){
                return response()->json([
                    'code' => $this->code,
                    'msg' => $this->msg,
                    'data' => $this->data,
                ])->setEncodingOptions(JSON_UNESCAPED_UNICODE)->cookie($this->cookie);
            }else{
                return response($this->string)->cookie($this->cookie);
            }
        }else{
            if($this->arr){
                return response()->json([
                    'code' => $this->code,
                    'msg' => $this->msg,
                    'data' => $this->data,
                ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
            }else{
                return response($this->string);
            }
        }

        //return view('pages.error', ['msg' => $this->message]);
    }

}
