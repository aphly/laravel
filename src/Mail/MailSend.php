<?php

namespace Aphly\Laravel\Mail;

use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status = false;

    function do($email,$obj){
        if($this->status && $email){
            Mail::to($email)->send($obj);
        }
    }
}
