<?php

namespace Aphly\Laravel\Mail;

use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status;

    public function __construct($bool = false){
        $this->status = $bool;
    }

    function do($email,$obj){
        if($this->status && $email){
            Mail::to($email)->send($obj);
        }
    }
}
