<?php

namespace Aphly\Laravel\Requests;

use Illuminate\Foundation\Http\FormRequest as baseRequest;
use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Contracts\Validation\Validator;

class FormRequest extends baseRequest
{
    public function authorize()
    {
        return false;
    }

    public function rules()
    {
        return [
            //
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->all();
        throw new ApiException(['code'=>11000,'msg'=>'表单验证错误','data'=>$message]);
    }
}
