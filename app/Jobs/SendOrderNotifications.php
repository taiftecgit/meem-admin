<?php

namespace App\Jobs;

use App\RestoSMSs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendOrderNotifications implements ShouldQueue
{
    
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $params;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $url = $this->params['url'];
        $message_data = $this->params['message_data'];
        $sms_id = $this->params['sms_id'];
        $response = Http::acceptJson()->post($url,$message_data);



        $response = $response->json();
        $response = json_encode($response);
        Log::info('TRACK_ORDER_SMS Response: '.$response);
        Log::info('Job Started: '.$sms_id);

        $sms = RestoSMSs::find($sms_id);
        $sms->otp_req = json_encode($message_data);

        $resp = json_decode($response);
        $sms->otp_req_status = strtoupper($resp->status)=="ACCEPTED"?"SUCCESS":"FAIL";



        $sms->otp_res = $response;


        $sms->save();
    }
}
