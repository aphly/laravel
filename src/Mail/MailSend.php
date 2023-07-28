<?php

namespace Aphly\Laravel\Mail;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Jobs\Email;
use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status;

    public function __construct(
        public bool $queue=true
    ){
        $this->status = config('base.mail_status');
    }

    function do($email,$obj,$queue_priority=0,$callback=false){
        if($this->status && $email){
            if($this->queue){
                Email::dispatch($email, $obj,$queue_priority,$callback);
            }else{
                try{
                    Mail::to($email)->send($obj);
                    if($callback){
                        $callback->handle();
                    }
                }catch (\Exception $e){
                    throw new ApiException(['code'=>1,'msg'=>'mail send error '.$e->getMessage()]);
                }
            }
        }
    }
}
