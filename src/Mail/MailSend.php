<?php

namespace Aphly\Laravel\Mail;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Jobs\Email;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailSend
{
    public bool $status;

    public function __construct(
        public bool $queue=true
    ){
        $this->status = config('base.mail_status');
    }

    function do($email,$obj,$queue_priority=0,$callback=false,$smtp=false){
        if($this->status && $email){
            if($this->queue){
                Email::dispatch($email, $obj,$queue_priority,$callback,$smtp);
            }else{
                try{
                    if($smtp){
                        Config::set('mail.mailers.smtp.host',$smtp->smtp_host);
                        Config::set('mail.mailers.smtp.port',$smtp->smtp_port);
                        Config::set('mail.mailers.smtp.encryption',$smtp->smtp_encryption);
                        Config::set('mail.mailers.smtp.username',$smtp->smtp_username);
                        Config::set('mail.mailers.smtp.password',$smtp->smtp_password);
                        Config::set('mail.from.address',$smtp->smtp_from_address);
                        Config::set('mail.from.name',$smtp->smtp_from_name);
                    }
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
