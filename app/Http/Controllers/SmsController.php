<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmsController extends Controller
{
    function send_sms($reason, $tran_type='T', $request_type='S',$apicode='14', $message, $numbers){
        $url = 'https://gpcmp.grameenphone.com/gp/ecmapigw/webresources/ecmapigw.v3';
        $uniqueCode25 = Str::lower(Str::random(25));

        $params = $params2 = [
            'username' => 'MGAdmin_4039',
            'password' => 'Mbrella@123_142',
            'apicode' => $apicode,
            'msisdn' => $numbers,
            'countrycode' => '880',
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
        dd( json_encode($params2));

        
        // Send GET request
        $response = Http::post($url, $params);

        

        // Return the response from the API or handle it as needed
        return $response->json();
    }
}
