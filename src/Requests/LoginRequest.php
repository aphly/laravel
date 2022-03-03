<?php

namespace Aphly\Laravel\Requests;

use Illuminate\Validation\Rule;

class LoginRequest extends FormRequest
{

    public function rules()
    {
        if($this->isMethod('post')){
            $input = $this->only('identifier','credential');
            return [
                'identifier' => ['required',
                        Rule::unique('user_auth')->where(function ($query)use($input){
                            return $query->where($input);
                        })
                    ],
                'credential' => 'required|between:6,64|alpha_num',
            ];
        }
        return [];
    }

//    public function attributes()
//    {
//        return [
//            'username'      => '用户名',
//            'password'      => '密码',
//        ];
//    }

    public function messages()
    {
        return [
            'username.required' => '请输入用户名',
            'password.required' => '请输入密码',
        ];
    }


}
