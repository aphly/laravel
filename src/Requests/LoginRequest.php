<?php

namespace Aphly\Laravel\Requests;

class LoginRequest extends FormRequest
{

    public function rules()
    {
        if($this->isMethod('post')){
            return [
                //'username' => 'required|between:4,32|alpha_num',
                'username' => 'required',
                'password' => 'required|between:6,64|alpha_num',
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
