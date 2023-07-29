<?php

namespace Aphly\Laravel\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class Email implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    //public $tries = 1;

    public $timeout = 60;

    public $failOnTimeout = true;

    //php artisan queue:work --queue=email_vip,email

    public function __construct(
        public $email,
        public $mail_obj,
        public $queue_priority=1,
        public $callback=false,
        public $smtp=false,
    ){
        if($queue_priority==1){
            $this->onQueue('email_vip');
        }else{
            $this->onQueue('email');
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->email && $this->mail_obj){
            if($this->smtp){
                Config::set('mail.mailers.smtp.host',$this->smtp->smtp_host);
                Config::set('mail.mailers.smtp.port',$this->smtp->smtp_port);
                Config::set('mail.mailers.smtp.encryption',$this->smtp->smtp_encryption);
                Config::set('mail.mailers.smtp.username',$this->smtp->smtp_username);
                Config::set('mail.mailers.smtp.password',$this->smtp->smtp_password);
                Config::set('mail.from.address',$this->smtp->smtp_from_address);
                Config::set('mail.from.name',$this->smtp->smtp_from_name);
            }
            Mail::to($this->email)->send($this->mail_obj);
            if($this->callback){
                $this->callback->handle();
            }
        }
    }
}
