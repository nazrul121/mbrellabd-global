<?php

namespace App\Services;
use Illuminate\Support\Str;
use App\Models\Sms;
use Illuminate\Support\Facades\Http;


class GPSMS
{
    
    function send_sms($reason, $message, $numbers, $tran_type = 'T', $request_type = 'S', $apicode = '1', $countrycode = '880', $orderId=null) {
      
        $url = 'https://gpcmp.grameenphone.com/gp/ecmapigw/webresources/ecmapigw.v3';
        $uniqueCode25 = Str::lower(Str::random(25));

        $params = $params2 = [
            'username' => 'MGAdmin_4039',
            'password' => 'Mbrella@123_142',
            'apicode' => $apicode,
            'msisdn' => $numbers,
            'countrycode' => $countrycode,
            'cli' => 'Mbrella',
            'messagetype' => '1',
            'message' => $message,
            'clienttransid' => $uniqueCode25,
            'bill_msisdn' => '01708404039',
            'tran_type' => $tran_type,
            'request_type' => $request_type,
            'rn_code' => '71'
        ];


        // save the message in db 
        $params2['username'] = 'hidden';
        $params2['password'] = 'hidden';
        $params2['order_id'] = $orderId;
        unset($params2['clienttransid']);

        // dd($url, $params);
        // $checkSms = Sms::where('message',json_encode($params2));
        // if($checkSms->count() <1){
        //     Sms::create([
        //         'clienttransid'=>$uniqueCode25,
        //         'purpose'=>$reason,
        //         'order_id'=>$orderId,
        //         'message'=>json_encode($params2)
        //     ]);
        // }
       
        // Send GET request
        $response = Http::post($url, $params);
        //return $response;

    }




    function send_custom_sms($reason, $message, $numbers, $tran_type = 'T', $request_type = 'S', $apicode = '1', $countrycode = '880') {
      
        $url = 'https://gpcmp.grameenphone.com/gp/ecmapigw/webresources/ecmapigw.v3';
        $uniqueCode25 = Str::lower(Str::random(25));

        $params = $params2 = [
            'username' => 'MGAdmin_4039',
            'password' => 'Mbrella@123_nz',
            'apicode' => $apicode,
            'msisdn' => $numbers,
            'countrycode' => $countrycode,
            'cli' => 'Mbrella',
            'messagetype' => '1',
            'message' => $message,
            'clienttransid' => $uniqueCode25,
            'bill_msisdn' => '01708404039',
            'tran_type' => $tran_type,
            'request_type' => $request_type,
            'rn_code' => '71'
        ];

        // save the message in db 
        $params2['username'] = 'hidden';
        $params2['password'] = 'hidden';
   
        unset($params2['clienttransid']);

        // dd($url, $params);
        $checkSms = Sms::where('message',json_encode($params2));
        if($checkSms->count() <1){
            Sms::create([
                'clienttransid'=>$uniqueCode25,
                'purpose'=>$reason,
                'order_id'=>null,
                'message'=>json_encode($params2)
            ]);
        }
        // dd( json_encode($params2), $params, $checkSms->count());

    
        // Send GET request
        $response = Http::post($url, $params);
        return $response;

    }
    
}