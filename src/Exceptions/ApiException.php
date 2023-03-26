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

    private $isArr;

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
            $this->isArr = true;
        }else{
            $this->isArr = false;
            $this->string = $arr;
        }
    }

    public function getData()
    {
        return $this->data;
    }

    public function resCookie(){
        return $this->res()->cookie($this->cookie);
    }

    public function res(){
        return response()->json([
            'code' => $this->code,
            'msg' => $this->msg,
            'data' => $this->data,
        ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function render(Request $request)
    {
        if($request->expectsJson()) {
            if($this->cookie){
                if($this->isArr){
                    return $this->resCookie();
                }else{
                    return response($this->string)->cookie($this->cookie);
                }
            }else{
                if($this->isArr){
                    return $this->res();
                }else{
                    return response($this->string);
                }
            }
        }else{
            if($this->cookie){
                if($this->isArr){
                    if(isset($this->data['redirect'])){
                        redirect($this->data['redirect'])->cookie($this->cookie)->send();
                    }else{
                        return $this->resCookie();
                    }
                }else{
                    return response($this->string)->cookie($this->cookie);
                }
            }else{
                if($this->isArr){
                    if(isset($this->data['redirect'])){
                        return redirect($this->data['redirect']);
                    }else{
                        return $this->res();
                    }
                }else{
                    return response($this->string);
                }
            }
        }
        // $this->cookie = cookie('name', 'value', $minutes);
        //return view('pages.error', ['msg' => $this->message]);
    }

}
