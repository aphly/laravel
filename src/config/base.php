<?php
return [
    'email'=>'121099327@qq.com',
    'perPage'=>'10',
    //全局邮件开关
    'mail_status'=>true,
    //跨越
    'cross' => [
        'http://localhost'
    ],
    //图片验证码
    'seccode_login'=>2, //0 1 2
    'seccode_register'=>1, //0 1
    'seccode_forget'=>1, //0 1
    'seccode_admin_login'=>0, //0 1

    'wechat' => [
        'client_id' => 'appid',
        'client_secret' => 'appSecret',
        'redirect' => 'http://xxxxxx.proxy.qqbrowser.cc/oauth/callback/driver/wechat',
    ]
];
