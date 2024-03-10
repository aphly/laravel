<?php

namespace Aphly\Laravel\Requests;

use Illuminate\Validation\Rule;

class AccountRequest extends FormRequest
{

    public function rules()
    {
        if($this->isMethod('post')){
            $str = $this->route()->getAction()['controller'];
            list($routeControllerName, $routeActionName) = explode('@',$str);
            $id_type = $this->input('id_type');
            if($routeActionName=='register'){
                if($id_type=='email'){
                    $where = ['id'=>$this->id,'id_type'=>$id_type];
                    return [
                        'id'   =>['required','email:filter',
                            Rule::unique('admin_user_auth')->where(function ($query) use ($where){
                                return $query->where($where);
                            })
                        ],
                        'password'   =>['required','between:6,64','alpha_num','confirmed'],
                        'password_confirmation'   =>['required','same:password'],
                    ];
                }else if($id_type=='mobile'){
                    $where = ['id'=>$this->id,'id_type'=>$id_type];
                    return [
                        'id'   =>['required','regex:/^1\d{10}$/',
                            Rule::unique('admin_user_auth')->where(function ($query) use ($where){
                                return $query->where($where);
                            })
                        ],
                        'password'   =>['required','between:6,64','alpha_num','confirmed'],
                        'password_confirmation'   =>['required','same:password'],
                    ];
                }
            }else if($routeActionName=='login'){
                if($id_type=='email') {
                    return [
                        'id' => 'required|email:filter',
                        'password' => 'required|between:6,64|alpha_num',
                    ];
                }else if($id_type=='mobile'){
                    return [
                        'id' => ['required','regex:/^1\d{10}$/'],
                        'password' => 'required|between:6,64|alpha_num',
                    ];
                }
            }else if($routeActionName=='forget'){
                if($id_type=='email') {
                    return [
                        'id' => 'required|email:filter'
                    ];
                }
            }else if($routeActionName=='forgetPassword'){
                return [
                    'password' => 'required|between:6,64|alpha_num',
                ];
            }
        }
        return [];
    }

    public function messages()
    {
        return [
            'id.required' => 'Please enter your email',
            'id.email' => 'The email must be a valid email address.',
            'id.unique' => 'The email has already been taken.',
            'id.regex' => 'Mobile error',
            'password.required' => 'Please enter your password',
            'password.alpha_num' => 'Password can only be letters and numbers',
        ];
    }


}
