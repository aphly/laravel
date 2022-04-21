<?php

namespace Aphly\Laravel\Jobs;

use Aphly\Laravel\Mail\Express;
use Aphly\Laravel\Mail\MailSend;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

//use Illuminate\Support\Facades\Log;

class OrderJob implements ShouldQueue,ShouldBeUnique
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $order;

    public $uniqueFor = 60;

    public function uniqueId()
    {
        return $this->order->order_id;
    }

    public function __construct($order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        (new MailSend(true))->do($this->order->email,new Express($this->order));
        DB::table('test_order')->where(['order_id'=>$this->order->order_id])->update(['status'=>1]);
        //Log::info($this->order->email);
    }
}
