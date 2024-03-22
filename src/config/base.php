<?php
return [
//全局
    'local_host'=>'http://atown.com',
    //默认：laravel-front-base
    'view_namespace_front_blade'=>'laravel-front',

    'email'=>'121099327@qq.com',
    'perPage'=>'10',
    'oss'=>true,
    //邮件开关
    'mail_status'=>true,
    //跨越
    'cross' => [
        'http://admin.chat.com'
    ],
    //图片验证码
    //2代表登录错误超限后出现,0代表关,1代表开
    'seccode_login'=>2, //0 1 2
    'seccode_register'=>0, //0 1
    'seccode_forget'=>1, //0 1
    'seccode_admin_login'=>0, //0 1

    //限流 10秒最多20次
    'limit'=>[
        'maxAttempts'=>20,
        'decaySeconds'=>10
    ],

//后端
    //特殊路径
    'admin'=>'tadmin',

//front
    //导航id
    'link_id'=>1,

    'title'=>'xxx',

    //注册类型
    'id_type'=>['email'],  //email || mobile

    //邮件激活
    'email_verify'=>false,

    //发送邮件类型 0同步 1队列
    'email_type'=>1,

    //发送邮件队列通道 1vip 0普通
    'email_queue_priority'=>0,

    //快捷注册
    'oauth'=>[
        'type'=>'id', //id || email
        'providers'=>[
            'facebook',
            'google',
        ]
    ],

    'email_appid'=>'2023080188980024',
    'email_secret'=>'nw30ZFKpOGjm3KwIoWcTSgbiRs6RXR3k',

    'statistics_appid'=>'2023092600946526',

    'wechat' => [
        'client_id' => 'appid',
        'client_secret' => 'appSecret',
        'redirect' => 'http://xxxxxx.proxy.qqbrowser.cc/oauth/callback/driver/wechat',
    ],
];
