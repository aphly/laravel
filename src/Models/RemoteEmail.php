<?php

namespace Aphly\Laravel\Models;

use Aphly\Laravel\Mail\MailSend;

class RemoteEmail
{
    function send($input){
        $MailSend = new MailSend();
        $MailSend->appid = config('base.email_appid');
        $MailSend->secret = config('base.email_secret');
        return $MailSend->remote($input);
    }
}
