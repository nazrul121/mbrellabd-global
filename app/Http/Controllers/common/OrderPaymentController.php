<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Order_payment;
use App\Models\Order_status;
use App\Models\Order_status_change;
use App\Models\Payment_type;
use App\Models\User;
use Illuminate\Http\Request;
use Auth; use Validator;

class OrderPaymentController extends Controller
{

    function takeApayment(Request $request, Order $order){
        if($request->draw){
            $data = Order_payment::where('order_id',$order->id)->orderBy('id', 'DESC');
            // dd($data->get());

            return datatables()::of($data)
            ->addIndexColumn()

            ->editColumn('payment_type', function($row){
                $dataArray = json_decode($row->payer_info, true);

                $media= '';
                if ($dataArray !=null && array_key_exists('reference', $dataArray)){
                    $media =  'portPos';
                }

                if ($dataArray !=null && array_key_exists('tran_id', $dataArray)){
                    $media =  'SSLCOmmerz';
                }

                if($row->payment_type_id==null) $paymentType = 'Not taken';
                else $paymentType = $row->payment_type->title;
                return $paymentType.' - '.$media;
            })

            ->editColumn('order_info', function($row){
                if($row->payer_info !='customer_info'){
                    $dataArray = json_decode($row->payer_info, true);

                    if ($dataArray !=null && array_key_exists('reference', $dataArray)){
                        return ' vai PortPos';
                    }

                    if ($dataArray !=null && array_key_exists('tran_id', $dataArray)){
                        return '<b>Card issuer:</b> '.$dataArray['card_issuer'].
                        '<br/><b>Trans. date:</b> '.$dataArray['tran_date']. 
                        '<br/><b>Card type</b>: '.$dataArray['card_type'].
                        '<br/><b>Card brand</b>: '.$dataArray['card_brand'].
                        '<br/><b>Status</b>: '.$dataArray['status'];
                    }

                    if($dataArray !=null && array_key_exists('payment_type', $dataArray)){
                        return 'Manual entry';
                    }
                    

                    // return 'reference';
                    // return '<b>Card issuer:</b> '.$dataArray['card_issuer'].
                    // '<br/><b>Trans. date:</b> '.$dataArray['tran_date']. 
                    // '<br/><b>Card type</b>: '.$dataArray['card_type'].
                    // '<br/><b>Card brand</b>: '.$dataArray['card_brand'].
                    // '<br/><b>Status</b>: '.$dataArray['status'];
                }
            })
            ->editColumn('received_by', function($row){
                if($row->user_id ==null){
                    return 'online payment';
                }else{
                    $user = User::find($row->user_id);
                    if($user->user_type->title=='superAdmin') $role ='admin';
                    else $role = $user->user_type->title;
                    // return $row->user->$role->first_name;
                    return $user->$role->first_name;
                }
            })
            ->rawColumns(['payment_type','order_info','received_by'])->make(true);
        }
        $orderStatusID = Order_status::where('relational_activity','ask-for-payment')->pluck('id')->first();
        // $stepUptoPayment = Order_status_change::where(['order_id'=>$order->id,'order_status_id'=>$orderStatusID])->count();
        return view('common.order.include.payment-info', compact('order','orderStatusID'));
    }

    function check_order_payment(Request $request, Order $order){
        $payment = Order_payment::where('order_id',$order->id)->get();
        $data = array();
        // dd($payment);
        if($payment->count() <1){ $data[0] = false; $data[1] = '';}
        else { $data[0] = true;
            $payments = array();
            foreach($payment as $pay){
                if( $pay->payment_type_id==null) $pay['payment_type'] = 'not taken';
                else $pay['payment_type'] = $pay->payment_type->title;
                $payments[] = $pay;
            }  $data[1] = $payments;
        }
        return $data;
    }

    public function create_payment(Request $request, Order $order){
        $validator = $this->fields();
        if ($validator->fails()) { return response()->json(['errors' => $validator->errors()->all()]);}

        $name = Payment_type::where('id',$request->payment_type)->pluck('title')->first();

        $customer_info = [
            'status' => 'VALID', 'amount'=>$request->amount, 'currency'=>"BDT", 'payment_type'=>"manual"
        ];
   
        Order_payment::create([
            'payment_type_id'=>$request->payment_type, 'order_id'=>$order->id, 'transaction_id'=>$order->transaction_id,
            'amount'=>$request->amount, 'payer_info'=>json_encode($customer_info),
            'user_id'=>Auth::user()->id, 'status'=>'VALID'
        ]);
        return response()->json(['success' => 'The payment hasn been taken successfully!']);
    }

    private function fields($id=null){
        $validator = Validator::make(request()->all(), [
            'payment_type'=>'required',
            'amount'=>'required|numeric'
        ]); return $validator;
    }

}
