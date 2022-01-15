<?php
namespace Aphly\Laravel\Exceptions;

use Illuminate\Http\Request;

class ApiException extends \Exception
{
    private $data = [];
    /**
     * @var mixed|string
     */
    private $msg;
    /**
     * BusinessException constructor.
     *
     * @param array $arr
     */
    public function __construct($arr = [])
    {
        parent::__construct();
        $this->code = $arr['code'] ?? 0;
        $this->msg  = $arr['msg'] ?? '';
        $this->data = $arr['data'] ??'';
    }

    public function getData()
    {
        return $this->data;
    }

    public function render(Request $request)
    {
        if ($request->expectsJson()) {
        }
        return response()->json([
            'data' => $this->data,
            'code' => $this->code,
            'msg' => $this->msg,
        ])->setEncodingOptions(JSON_UNESCAPED_UNICODE);
        //return view('pages.error', ['msg' => $this->message]);
    }

}
