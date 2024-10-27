<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Order_payment;
use App\Models\Payment_type;
use DGvai\SSLCommerz\SSLCommerz;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class PaymentController extends Controller
{

    public function get_invoice(Order $order){
	
        $mode = env('PortPos_appMode');
        $appKey = env('PortPos_appKey');
        $secretKey = env('PortPos_secretKey');
        $url = env('PortPos_invoiceEndpoint');

        $amount = 0;
        if($order->invoice_discount !=null) {
            $amount +=($order->total_cost + $order->shipping_cost) - $order->invoice_discount;
        }
        else $amount +=$order->total_cost + $order->shipping_cost;
        
        if(strtolower($order->country->currency_code) !='bdt'){
            $totalAmount =  $order->country->currencyValue * $amount;
        }
        else$totalAmount = $amount;
        
        if($order->country_id==2){
            $billPHone = $order->phone;
            $customerCity = $order->city;
            $customerState = $order->district;
            $customerAddress = $order->address;
        }else{
            $billPHone = '01999080846';
            $customerCity = 'Dhaka';
            $customerState = 'Dhaka';
            $customerAddress = 'sector 3, Road 3, house 19, Uttara';
        }

        $products = '';
        foreach($order->order_items()->get() as $key=>$item){
            $products .= $item->product->title;
            if($key + 1 != $order->order_items()->count()) $products .= ', ';
        }

       
        $data = [
            'order' => [
                'amount' => $totalAmount,
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
                    'email' => $order->email,
                    'phone' => $billPHone,
                    'address' => [
                        'street' => $customerAddress,
                        'city' => $customerCity,
                        'state' => $customerState,
                        'zipcode' => 'unknown',
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

            // dd($response->error);

            $response = [
                'cause' => 'Payment Error',
                'message' =>$response->error->explanation,
                'orderLink' => route('order-info',$order->transaction_id)
            ];
        
            return view('errors.400', $response);
            // abort(400, $response->error->cause, [
            //     'message' => $response->error->explanation , 
            //     'orderLink'=> route('order-info',$order->transaction_id)
            // ]);
        }
        $url = $response->data->action->url;
        
        return redirect($url);
    }

    function portPost_return(Request $request){
        $order = Order::where('id',$request->reference)->first();
        Order_payment::create([
            'order_id'=>$request->reference,
            'payment_type_id'=>1,
            'transaction_id'=>$order->transaction_id,
            'bank_tran_id'=>null,
            'amount'=>$request->amount,
            'payer_info'=>json_encode($request->all()),
            'status'=>$request->status
        ]);
        
        return redirect()->route('order-info',$order->transaction_id);

    }
    
    
    
    
    
    public function order(Order $order){
        $amount = 0;
        if($order->invoice_discount !=null) {
            $amount +=($order->total_cost + $order->shipping_cost) - $order->invoice_discount;
        }else{
            $amount +=$order->total_cost + $order->shipping_cost;
        }
        $sslc = new SSLCommerz();
        
        if(strtolower($order->country->currency_code) !='bdt') $currency = 'USD'; else $currency = 'BDT';

        $sslc->amount($amount)
            ->setCurrency($currency)
            ->trxid($order->transaction_id)
            ->product('Total of '.$order->order_items()->count().' product item')
            ->customer($order->first_name.' '.$order->last_name, $order->phone);

        $sslc->setExtras($order->id);
        return $sslc->make_payment();
    }



    public function success(Request $request){
        $validate = SSLCommerz::validate_payment($request);
        if($validate){
            // dd($request->all());
            $bankID = $request->bank_tran_id;   //  KEEP THIS bank_tran_id FOR REFUNDING ISSUE
            Order_payment::create([
                'order_id'=>$request->value_a,
                'payment_type_id'=>1,
                'transaction_id'=>$request->tran_id,
                'bank_tran_id'=>$bankID,
                'amount'=>$request->amount,
                'payer_info'=>json_encode($request->all()),
                'status'=>$request->status
            ]);
            return redirect()->route('order-info',$request->tran_id);
        }
    }

    public function failure(Request $request)
    {
        $data = [
            'order_id'=>$request->value_a,
            'payment_type_id'=>1,
            'transaction_id'=>$request->tran_id,
            'bank_tran_id'=>null,
            'amount'=>$request->amount,
            'payer_info'=>json_encode($request->all()),
            'status'=>$request->status
        ];
        Order_payment::create($data);
        return redirect()->route('order-info',$request->tran_id);
        //  do the database works
        //  also same goes for cancel()
        //  for IPN() you can leave it untouched or can follow
        //  official documentation about IPN from SSLCommerz Panel

    }


    public function cancel (Request $request)
    {
        // dd($request->all());
        $data = [
            'order_id'=>$request->value_a,
            'payment_type_id'=>1,
            'transaction_id'=>$request->tran_id,
            'bank_tran_id'=>null,
            'amount'=>$request->amount,
            'payer_info'=>json_encode($request->all()),
            'status'=>$request->status
        ];
        Order_payment::create($data);

        return redirect()->route('order-info',$request->tran_id);
    }



}
