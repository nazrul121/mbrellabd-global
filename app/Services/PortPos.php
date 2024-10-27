<?php

namespace App\Services;

class PortPos
{
    
    public function get_invoice($order){
     
        $mode = env('PortPos_appMode');
        $appKey = env('PortPos_appKey');
        $secretKey = env('PortPos_secretKey');
        $url = env('PortPos_invoiceEndpoint');

        $amount = 0;
        if($order->invoice_discount !=null) {
            $amount +=($order->total_cost + $order->shipping_cost) - $order->invoice_discount;
        }
        else $amount +=$order->total_cost + $order->shipping_cost;
        
        // if(strtolower($order->country->currency_code) !='bdt') $currency = 'USD'; else $currency = 'BDT';

        $products = '';
        foreach($order->order_items()->get() as $key=>$item){
            $products .= $item->product->title;
            if($key + 1 != $order->order_items()->count()) $products .= ', ';
        }

        if($order->country_id==2){
            $customerPhone = $order->phone;
            $customerCity = $order->city;
            $customerState =- $order->district;

        }else {
            $customerPhone = '01729069705';
            $customerCity = 'Dhaka';
            $customerState = 'Dhaka';
        }

        $data = [
            'order' => [
                'amount' => $amount,
                'currency' => 'BDT',
                'redirect_url' => route('portPost-return'),
                'ipn_url' => route('portPost-return'),
                'reference' => $order->id,
            ],
            'product' => [
                'name' => 'Total of '.$order->order_items()->count().' product item',
                'description' =>  $products
            ],
            'billing' => [
                'customer' => [
                    'name' => $order->first_name.' '.$order->last_name,
                    'email' => $order->email??'no@email.com',
                    'phone' => $customerPhone,
                    'address' => [
                        'street' => $order->address,
                        'city' => $customerCity,
                        'state' => $customerState,
                        'zipcode' => 'Unknown',
                        'country' => 'BD'
                    ]
                ]
            ]
        ];
    
   
        // dd($data);
        $headers[] = 'Authorization: ' . 'Bearer ' . base64_encode($appKey . ":" . md5($secretKey . time()));
        $headers[] = 'Content-Type: ' . 'application/json';
       
        $data = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);     

       
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $info = curl_getinfo($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if (substr($info['http_code'],0,2) == 20 && !empty($response)) {
            $response= json_decode($response);
        }
        else $response= json_decode($response);

        if($response->result == 'ERROR'){
            dd($response);
        }
        $url = $response->data->action->url;
        
        return redirect($url);
    }



}
