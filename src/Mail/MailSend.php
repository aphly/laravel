<?php

namespace Aphly\Laravel\Mail;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Jobs\Email;
use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status;

    public function __construct(
        public bool $queue=false
    ){
        $this->status = config('admin.mail_status');
    }

    function do($email,$obj,$queue_priority='email'){
        if($this->status && $email){
            if($this->queue){
                Email::dispatch($email, $obj,$queue_priority);
            }else{
                try{
                    Mail::to($email)->send($obj);
                }catch (\Exception $e){
                    throw new ApiException(['code'=>1,'msg'=>'mail send error '.$e->getMessage()]);
                }
            }
        }
    }
}
