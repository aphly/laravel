<?php

namespace Aphly\Laravel\Jobs;

use Aphly\Laravel\Mail\MailSend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
        public $queue_priority,
    ){
        if($queue_priority=='email_vip'){
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
            Mail::to($this->email)->send($this->mail_obj);
        }
    }
}
