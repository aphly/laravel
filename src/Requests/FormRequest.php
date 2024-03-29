<?php

namespace Aphly\Laravel\Requests;

use Illuminate\Foundation\Http\FormRequest as baseRequest;
use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;

class FormRequest extends baseRequest
{

    public function authorize()
    {
        return true;
    }


    public function rules()
    {
        return [
            //
        ];
    }

    public function validate($arr,$rules=[],$msg=[])
    {
        $validator = \Illuminate\Support\Facades\Validator::make($arr,!empty($rules)?$rules:$this->rules(),!empty($msg)?$msg:$this->messages());
        if ($validator->fails()) {
            $this->fail($validator);
        }
    }

    protected function failedValidation(Validator $validator)
    {
        //if ($this->wantsJson() || $this->ajax()) {
            $this->fail($validator);
        //} else {
            //parent::failedValidation($validator);
        //}
    }

    public function fail($validator){
        throw new ApiException(['code'=>11000,'msg'=>'form error','data'=>$validator->errors()]);
    }
}
