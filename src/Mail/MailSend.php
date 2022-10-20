<?php

namespace Aphly\Laravel\Mail;

use Aphly\Laravel\Exceptions\ApiException;
use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status;

    public function __construct(){
        $this->status = config('admin.mail_status');
    }

    function do($email,$obj){
        if($this->status && $email){
            try{
                Mail::to($email)->send($obj);
            }catch (\Exception $e){
                throw new ApiException(['code'=>1,'msg'=>'mail send error '.$e->getMessage()]);
            }
        }
    }
}
