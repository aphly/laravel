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

    public function validate($arr)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($arr,$this->rules(),$this->messages());
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
        throw new ApiException(['code'=>11000,'msg'=>'表单验证错误','data'=>$validator->errors()->all()]);
    }
}
