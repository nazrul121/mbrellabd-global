<?php

namespace App\Http\Controllers\common;

use App\Exports\SslCommerzOrderExport;
use App\Exports\OrderExport2;
use App\Exports\OrderExport3;
use App\Http\Controllers\Controller;
use App\Models\Courier_company;
use App\Models\Courier_company_order;
use App\Models\Courier_order_bundle;
use App\Models\Courier_zone;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Order_payment;
use App\Models\Order_status;
use App\Models\Order_status_change;
use App\Models\Payment_type;
use App\Models\Product;
use App\Models\Dhl_shipment;
use App\Models\Shipping_address;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Services\DHL;
use DB;

class OrderController extends Controller
{
    
    function index(Order_status $order_status,Request $request){
        if($request->draw){
            $datatable = datatables()::of(Order::where('order_status_id',$order_status->id));
            return $this->orders($datatable, $order_status);
        }
        // $orders = Order::where('order_status_id',$order_status->id)->orderBy('created_at', 'DESC')->paginate(50);
        return view('common.order.index', compact('order_status'));
    }

    function all_orders(Request $request){
        // foreach( Order::orderBy('id', 'DESC')->get() as $order ){
        //     \App\Models\Order_status_change::create([ 'order_id'=>$order->id, 'order_status_id'=>'1'    ]);
        // }

        $order_status = new Order_status();
        $order_status->title = 'All orders'; $order_status->id=0;

        if($request->draw){
            $datatable = datatables()::of(Order::orderBy('created_at', 'DESC'));
            return $this->orders($datatable, $order_status);
        }
        return view('common.order.index', compact('order_status'));
    }

    function date_range($from, $to, Request $request){
        $order_status = (object)[];

        $a = str_replace('-', '/', $from);
        $b = str_replace('-', '/', $to);

        $date1 = date('Y-m-d',strtotime($a));
        $date2 = date('Y-m-d',strtotime($b));

        $order_status->id = 0;
        $order_status->title = date('d M, Y',strtotime($a)).' - '.date('d M, Y',strtotime($b)).' orders';

        if($request->draw){
            $datatable = datatables()::of( Order::whereBetween('order_date', [$date1, $date2])->orderBy('order_date', 'DESC'));
            return $this->orders($datatable, $order_status);
        }
        // $orders = Order::where('order_status_id',$order_status->id)->orderBy('created_at', 'DESC')->paginate(50);
        return view('common.order.index', compact('order_status'));
    }

    function shipping_address(Order $order){
        return view('common.order.include.shipping-address',compact('order'));
    }

    public function order_invoice($orderId){
        $order = Order::where('id',$orderId)->first();
        if($order==null){
            $order = Order::where('invoice_id',$orderId)->first();
        }
        return view('common.order.invoice',compact('order'));
    }

    function delivery_process(Request $request, Order $order){
        if($request->status){
            $status = Order_status::find($request->status);
            $preStatusId = Order_status::where('id', '<', $status->id)->max('id');

            // dd($request->all());
            // dd($preStatusId);

            $checkWithPreStatus = Order_status_change::where(['order_status_id'=>$preStatusId, 'order_id'=>$order->id])->first();

            // dd($checkWithPreStatus);

            if($checkWithPreStatus == null && $status->action=='continue'){
                $data['1']= '';
                $data['0']= '<p class="text-danger alert alert-danger"> <span class="text-info">Sequence error!</span> &nbsp;  Please maintain status sequence</p>';
                return response()->json($data);
            }else{
                // dd($checkWithPreStatus);

                // $data['1']= '';
                // $data['0']= '<p class="text-danger alert alert-danger"> <span class="text-info">Sequence error!</span> &nbsp;  Please maintain status sequence</p>';
                // return response()->json($data);

            }

            // exit;
            $data = ['order_id'=>$order->id,'order_status_id'=>$request->status];


            // take payment

            if($request->avoid_payment){
                if($request->avoid_payment=='avoid'){
                    $paymentData =[
                        'order_id'=>$order->id,'transaction_id'=>$order->transaction_id,
                        'amount'=>'0','payer_info'=>' {"status":"Failed","desc":[ { "name":" " } ] }', 'status'=>'Failed'
                    ];

                    $check = Order_payment::where($paymentData);

                    if($check->count() <1){
                        $paymentData['user_id'] = Auth::user()->id;
                        Order_payment::create($paymentData);
                    }
                }
            }else{
                if($status->relational_activity=='ask-for-payment' && !is_numeric($request->amount)){
                    $data['1'] = '';
                    $data['0']= '<p class="text-danger alert alert-danger"> <span class="text-info">Warning:</span> &nbsp; Please Fill up the payment form correctly</p>';
                    return response()->json($data);
                }

                if($request->payment_type && $request->amount){
                    $name = Payment_type::where('id',$request->payment_type)->pluck('title')->first();
                    Order_payment::create([
                        'payment_type_id'=>$request->payment_type, 'order_id'=>$order->id, 'transaction_id'=>$order->transaction_id,
                        'amount'=>$request->amount, 'payer_info'=>' {"status":"SUCCESS","desc":[ { "name":"'.$name.'" } ] }',
                        'user_id'=>Auth::user()->id, 'status'=>'SUCCESS'
                    ]);
                }
            }


            if($request->note =='') $note = $request->status_text; else $note = $request->note;

            $check = Order_status_change::where($data);
            if($check->count() <1){
                $oStatus = Order_status_change::where(['order_id'=>$order->id])->orderBy('id','DESC')->first();
                $qty_status = Order_status::where('id',$request->status)->pluck('qty_status')->first();

                if($qty_status=='return-qty'){
                    //update qty at products and product_variants table
                    foreach($order->order_items()->get() as $item){
                        if($item->product_combination_id !=null){
                            $item->product_combination->update(['qty'=>$item->product_combination->qty + $item->qty]);
                        }else{
                            $item->product->update(['qty'=>$item->product->qty + $item->qty]);
                        }
                    }
                }

                if($oStatus==null || $oStatus->order_status->action !='non-editable'){
                    Order_status_change::create([
                        'order_id'=>$order->id,'order_status_id'=>$request->status,
                        'user_id'=>Auth::user()->id, 'note'=>$note
                    ]);
                    $data['0']= '<p class="text-success alert alert-success"> <b class="fas fa-check"></b> Status has been changed successfully!</p>';
                    $data['1']= '';
                    Order::where('id',$order->id)->update([ 'order_status_id'=>$request->status]);
                    $data['1']= 'reload';
                }
                else{
                    $data['1']= 'reload';
                    $data['0']= '<p class="text-danger alert alert-danger"> THe order status already been updated as <b class="text-info">'.$oStatus->order_status->title.'</b></p>';
                }

            }else{
                Order_status_change::where($data)->update(['note'=>$note]);
                $data['0']= '<p class="text-danger alert alert-warning"> Status has been changed before and <b class="text-info">Note</b> has been updated!</p>';
                $data['1']= '';
            }
            return response()->json($data);
        }

        elseif($request->draw){
            return datatables()::of(Order_status_change::where('order_id',$order->id)->orderBy('id', 'DESC'))
            ->addIndexColumn()
            ->editColumn('order_status_id', function($row){
                return  $row->order_status->title;
            })
            ->editColumn('date', function($row){
                return date('M d, Y H:i:a',strtotime($row->created_at));
            })
            ->editColumn('user_id', function($row){
                if($row->user_id !=null){
                    $role =  $row->user->user_type->title;
                    if($role=='superAdmin') $role = 'admin';
                    return  $row->user->$role->first_name;
                }

            })
            ->rawColumns(['order_status_id','date','user_id'])->make(true);
        }
        else{
            $order_status = Order_status:: get();
            return view('common.order.include.delivery',compact('order','order_status'));
        }
    }

    function prepare2ship($order_ids){
        $orderIDs = explode(',',$order_ids);
        $orders = Order::whereIn('id',$orderIDs)->get();
        return view('common.order.include.ready2ShipForm',compact('order_ids','orders'));
    }

    // save courier order bundles
    function saveTopSheet(Request $request){
        
        // dd($company);
        // dd($request->all());
        $bundle = Courier_order_bundle::create([
            'bundle_id'=>rand(),
            'user_id'=>Auth::user()->id,
            'courier_company_id'=>$request->company,
            'courier_representative_id'=>$request->company_man
        ]);
        foreach($request->order_ids as $key=>$order_id){
            $courier_zone = Courier_zone::where(['id'=> $request->zones[$key], 'courier_company_id'=>$request->company])->first();

            Courier_company_order::create([
                'order_id'=>$order_id,
                'courier_order_bundle_id'=>$bundle->id,
                'courier_zone_id'=>$request->zones[$key],
                'courier_company_id'=>$request->company,
                'delivery_cost'=>$courier_zone->delivery_cost,
                'return_cost'=>$courier_zone->return_cost,
            ]);

            $ship_status_id = Order_status::where('relational_activity','ship')->pluck('id')->first();
            Order_status_change::create([
                'order_id'=>$order_id,
                'order_status_id'=>$ship_status_id,
                'user_id'=>Auth::user()->id,
                'note'=>'Order status changes by order shipment',
            ]);

            Order::where('id',$order_id)->update(['order_status_id'=>$ship_status_id]);
        }
       
        session()->flash('success', 'The orders are shipped successfully.');
        return back();
    }


    private function orders($datatable, $order_status){
        return $datatable
        // ->addIndexColumn()
        ->editColumn('id', function ($row) use ($order_status) {
            if($order_status->id=='3'){
                return ' <input type="checkbox" class="selectedOrder" name="order_ids[]" style="width:25px;height:25px" value="'. $row->id.'">';
            } else return $row->id;
        })
        ->editColumn('transaction_id', function($row){
            $data = 'Transaction ID: '.$row->transaction_id.'<br/>
            <a class="btn btn-sm btn-primary pb-0 pt-0"  href="'.route('common.order.invoice',$row->id).'" target="_blank"><i class="fa fa-print"></i> View Invoice</a> <br>
            Status: '.$row->order_status->title.' <br>';

            if(check_access('change-order-status')){
                $data .='<button class="btn btn-sm btn-info pb-0 pt-0 deliveryPorcess" data-id="'.$row->id.'" type="button"><i class="fa fa-truck"></i> Delivery process</button> <br>';
            }

            if(check_access('take-order-payment')){
                $data .='<button class="btn btn-sm btn-warning pb-0 pt-0 payment" data-transaction_id="'.$row->transaction_id.'" data-id="'.$row->id.'" type="button"><i class="fas fa-money-bill-alt"></i> Payment</button>';
            }
            if(check_access('edit-order')){
                if($row->order_status_id==1){
                    $data .='<br/> <a class="btn btn-sm btn-success pb-0 pt-0 " target="_" href="'.route('common.edit-order',$row->id).'" type="button"><i class="fas fa-edit"></i> Edit order</a>';
                }
            } return $data;
        })
        ->editColumn('product_info', function($row){
            $data = ' <p class="bg-light p-2 text-center">Total of <b>'.$row->order_items()->where('status','!=','removed')->get()->count().'</b> order items</p>';
            foreach ($row->order_items()->where('status','!=','removed')->get() as $key1=>$order_item){
                $key1 += 1;
                if($order_item->product==null)
                    $data .='<p class="text-center text-danger">Order Item ID: <b>"'.$order_item->id.'"</b> relate the <b>Product ID</b> that does not exist!</p>';
                else{
                    $data .= '<a data-toggle="collapse" href="#collapse'.$order_item->product_id + $row->invoice_id+$key1.'" role="button" aria-expanded="false" aria-controls="collapse'.$order_item->product_id + $row->invoice_id+$key1.'"><h6 class="p-1">'.$key1.'. '.$order_item->product->title.'</h6> </a>';
                    if($order_item->product_combination_id !=null){
                        foreach ($order_item->product_combination()->get() as $key2 => $pComb){
                            $data .='<div class="collapse" id="collapse'.$order_item->product_id + $row->invoice_id+$key1.'">
                            <ul class="ml-2">';
                            foreach (explode('~',$pComb->combination_string) as $string){
                                $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();
                                
                                if($v) $vData='<li>'.$v->variation->title.': </b> '. $v->title.'</li>';
                                else $vData ='<li>'.$string.'</li>';
                                
                                $data .=$vData;
                            }
                            $data .='</ul>  </div>';
                        }
                    }
                }

            }
            return $data;
        })

        ->editColumn('customer_info', function($row){
           
            $data = '<b class="text-capitalize cusInfo'. $row->transaction_id.'">'.$row->first_name.' '.$row->last_name.' ('.$row->phone.')</b> <br>';
           
            $data .= 'Area: '.$row->division.' <i class="fa fa-arrow-right"></i> '; 
            $data .=  $row->district.' <i class="fa fa-arrow-right"></i> ';
            $data .= $row->city.' <br/>'; 

            $data .= ' <p style="white-space: break-spaces;"><b>Address:</b> '.$row->address.'</p>';

            if($row->note){
                $data .='<br> <button class="btn btn-sm btn-secondary pb-0 pt-0 note" data-transaction_id="'.$row->transaction_id.'" title="'. $row->note.'" type="button"><i class="fa fa-user"></i> Order Note</button>';
            }

            
            $data .='<button  class="btn btn-sm btn-secondary pb-0 pt-0 shippingInfo" data-transaction_id="'.$row->transaction_id.'" data-id="'.$row->id.'" type="button"><i class="fa fa-map-marker-alt"></i> Shipping address</button>';
            
           
            if($row->country_id !=2){
                $data .='<br/><button  class="btn btn-sm btn-info pb-0 pt-0 dhl" data-id="'.$row->id.'" type="button"> DHL shipment</button>';
            }
            return $data;
        })

        ->editColumn('order_info', function($row){
            $data ='';
            $discounts = $row->invoice_discount;
            $total = ($row->total_cost + $row->shipping_cost) - $discounts;

            if($row->country_id ==2){
                $data .= ' Date: '.date('d F Y',strtotime($row->order_date)).' <br>';
                $data .= 'Payment: '.strip_tags($row->payment_geteway->name).' <br>';

                $data .= 'Order in: '.$row->country->currency_code.' <br>';
                $data .= 'Order value: '.$row->country->currencySymbol.$row->total_cost.' <br>';
                $data .= 'Shipping charge: '. $row->country->currencySymbol.$row->shipping_cost.' <br>';
                $data .= 'Invocie Discount: '. $row->country->currencySymbol.$discounts.' <br>';
                $data .= 'Total amount:  '.$row->country->currencySymbol.$total.' <br>';
            }else{
                $data .= '<div class="row p-2" style="background: #ffc5c542;">';

                $data .= ' Date: '.date('d F Y',strtotime($row->order_date)).' <br>';
                $data .= 'Payment: '.strip_tags($row->payment_geteway->name).' <br>';
                $data .= 'Order from: '.$row->country->short_name.' <br>';

                $totalInLocal = $row->total_cost;
                $shippingInLocal = $row->shipping_cost;

                $total = $totalInUSD = $shippingInUSD = 0;

                $orderInDollar = DB::table('dollar_rate_order')->where('order_id',$row->id)->first();
                if($orderInDollar !=null && $shippingInLocal >0 && $totalInLocal>0){
                    $shippingInUSD = number_format( $shippingInLocal /$orderInDollar->value , 2);
                    $totalInUSD = number_format($totalInLocal/$orderInDollar->value , 2);
                }
                
                $data .= 'Order value: '.$row->country->currencySymbol.' '.$totalInLocal .'( $'.$totalInUSD.') <br>';
                $data .= 'Shipping charge: '.$row->country->currencySymbol.' '.$shippingInLocal.' ($'.$shippingInUSD.')'.' <br>';
                $data .= 'Total amount: '.$row->country->currencySymbol.' '.($totalInLocal + $shippingInLocal).' ($'.($totalInUSD + $shippingInUSD).') <br>';
                $data .='</div>';
            }
            
            return $data;
        })
        ->editColumn('ref', function($row){
            if($row->ref=='self') return 'Website';
            return $row->ref;
        })

        ->rawColumns(['id','transaction_id','product_info','customer_info','ref','order_info'])->make(true);
    }


    function invoice_base_orders(Request $request){
        if($request->draw){
            if($request->start_date){
                $start   = date('Y-m-d',strtotime(str_replace('-','/',$request->start_date)));
                $end     = date('Y-m-d',strtotime(str_replace('-','/',$request->end_date)));              
                $orders =Order::whereBetween('order_date', [$start, $end])->orderBy('order_date', 'DESC');
            }else{
                $orders= Order::orderBy('order_date', 'DESC');
            }

            return datatables()::of($orders)
          
            ->editColumn('order_date', function($row){
                return date('d M, Y',strtotime($row->order_date));
            })
    
            ->editColumn('customer', function($row){
                return $row->customer->phone;
            })
            ->editColumn('status', function($row){
                return $row->order_status->title;
            })
            ->editColumn('total_cost', function($row){
                return $row->order_items()->count();
            })
            ->editColumn('total_items', function($row){
                return '<b class="getOrderItems" style="cursor:pointer;" data-id="'.$row->id.'">'.$row->order_items()->count().'</b> <small>items</small>';
            })
    
            ->rawColumns(['order_date','invoice_id','customer','total_items','total_cost','status'])->make(true);
        }
        // return view('common.export.order', ['orders'=>$orders]);
        return view('common.order.invoice_base_orders');
    }

    function order_excel(Request $request, Excel $excel){
        
        // return Excel::download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        // return $excel->download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        return $excel->download(new OrderExport2($request->start_date, $request->end_date), 'order-report-'.date('d-m-Y').'.xlsx');
    }

    function order_pdf(Request $request){
        if($request->start_date){
            $start   = date('Y-m-d',strtotime(str_replace('-','/',$request->start_date)));
            $end     = date('Y-m-d',strtotime(str_replace('-','/',$request->end_date)));        
            $orders =Order::whereBetween('order_date', [$start, $end])->orderBy('created_at', 'DESC')->get();
        }else{
            $orders= Order::orderBy('created_at', 'DESC')->get();
        }

        // return view('common.export.order-wize.order', ['orders'=>$orders]);
        
        $pdf = PDF::setOption(['defaultFont' => 'sans-serif'])
        ->loadView('common.export.order-wize.order', ['orders'=>$orders])
        ->setPaper('a1', 'landscape');

        return $pdf->download('order-report-'.date('d-m-Y').'.pdf');
    }


    function single_product_orders(Request $request){
        if($request->product){
            $product = Product::where('design_code',$request->product)
                ->orWhere('title','LIKE','%'.$request->product.'%')
                ->orWhere('tags','LIKE','%'.$request->product.'%')
                ->first();
        }else $product = null;

        return view('common.order.single-product-report', compact('product'));
    }


    function sslcommerz_orders(Request $request){
        $statuses = Order_payment::where('payment_type_id','!=','2')->select('status')->distinct()->get();
        if($request->status !=''){
            $orderPayments = Order_payment::where('payment_type_id','!=','2')->where('status',$request->status)->orderBy('id','DESC')->get();
        }else{
            $orderPayments = Order_payment::where('payment_type_id','!=','2')->orderBy('id','DESC')->get();
        }
        
        return view('common.order.sslcommerz-order', compact('orderPayments', 'statuses'));
    }
    

    function sslcommerz_excel(Request $request, Excel $excel){
        // return Excel::download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        // return $excel->download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        if($request->status=='') $status = 'all';
        else $status = strtolower($request->status);
        return $excel->download(new SslCommerzOrderExport($request->status,), $status.'-online-payment-report@-'.date('d-m-Y').'.xlsx');
    }

    function deliverd_orders(Request $request, Excel $excel){
       
        // return view('common.export.deliverd-order-info', compact('orders'));
        return $excel->download(new OrderExport3($request->start_date, $request->end_date), 'delivered-order-report@-'.date('d-m-Y').'.xlsx');
    }

    function order_dhl(Order $order){
        // dd($order);
        $shipment = Dhl_shipment::where('order_id',$order->id)->orderBy('id','DESC')->first();
        return view('common.order.order-dhl-modal', compact('order','shipment'));
    }

    function reorder_dhl(Order $order){
        $dhl_method = new DHL();
        $dhl_shipment =  json_encode($dhl_method->create_shipment($order));
    }

    function create_dhl_pickup(Order $order){
        $dhl_method = new DHL();
        $dhl_shipment =  json_encode($dhl_method->create_pickup($order));
    }


}
