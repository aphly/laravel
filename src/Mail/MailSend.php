<?php

namespace Aphly\Laravel\Mail;

use Aphly\Laravel\Exceptions\ApiException;
use Aphly\Laravel\Jobs\Email;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use PHPUnit\Exception;

class MailSend
{
    public bool $status;

    public function __construct(
        public bool $queue=true,
        public  $appid='',
        public  $secret=''
    ){
        $this->status = config('base.mail_status');
    }

    function config($smtp){
        Config::set('mail.mailers.smtp.host',$smtp->smtp_host);
        Config::set('mail.mailers.smtp.port',$smtp->smtp_port);
        Config::set('mail.mailers.smtp.encryption',$smtp->smtp_encryption);
        Config::set('mail.mailers.smtp.username',$smtp->smtp_username);
        Config::set('mail.mailers.smtp.password',$smtp->smtp_password);
        Config::set('mail.from.address',$smtp->smtp_from_address);
        Config::set('mail.from.name',$smtp->smtp_from_name);
    }

    function do($email,$obj,$queue_priority=0,$callback=false,$smtp=false,$is_cc=0){
        if($this->status && $email){
            if($this->queue){
                Email::dispatch($email, $obj,$queue_priority,$callback,$smtp,$this,$is_cc);
            }else{
                try{
                    $cc = 0;
                    if($smtp){
                        $this->config($smtp);
                        $cc = $is_cc?$smtp->cc:0;
                    }
                    if($cc){
                        Mail::to($email)->cc($cc)->send($obj);
                    }else{
                        Mail::to($email)->send($obj);
                    }
                    if($callback){
                        $callback->handle();
                    }
                }catch (\Exception $e){
                    throw new ApiException(['code'=>1,'msg'=>'mail send error '.$e->getMessage()]);
                }
            }
        }
    }

    function remote($input){
        if($this->appid && $this->secret){
            $input['timestamp'] = time();
            $input['appid'] = $this->appid;
            $input['sign'] = md5(md5($input['appid'].$input['email'].$this->secret).$input['timestamp']);
            try{
                $res = Http::connectTimeout(5)->post('https://email.apixn.com/email/send',$input);
            }catch (Exception $e){
                return $e->getMessage();
            }
        }
        return '';
    }
}
