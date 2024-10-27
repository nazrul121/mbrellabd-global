<?php

namespace App\Http\Controllers\common;

use App\Exports\OrderExport;
use App\Http\Controllers\Controller;
use App\Models\Courier_company;
use App\Models\Courier_order_bundle;
use App\Models\Order;
use App\Models\Order_item;
use App\Models\Order_status;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExportOrderController extends Controller
{
    function order_report(Request $request){
        if($request->draw){
            if($request->start_date){
                // print_r($request->all()); echo '<br/>';
                $start = date('Y-m-d',strtotime(str_replace('-','/',$request->start_date)));
                $end = date('Y-m-d',strtotime(str_replace('-','/',$request->end_date)));
                // echo 'start: '.$request->start.', end: '.$request->end.'<br/>';
                // dd('start: '.$start.', end: '.$end);
    
                $order_ids = Order::whereBetween('order_date', [$start, $end])->select('id')->get()->toArray();
              
                $datatable = datatables()::of(Order_item::whereIn('order_id', $order_ids)->orderBy('created_at', 'DESC')->get());

            }else{
                // dd(Order_item::orderBy('created_at', 'DESC')->get());
                // $datatable = datatables()::of(Order_item::orderBy('created_at', 'DESC'));
                $datatable = datatables()::of(Order_item::where('id', '<', '1'));
            }

            return $this->order_reports($datatable);
        }
        // $order_ids = Order::select('id')->get()->toArray();
        // $orders = Order_item::whereIn('order_id', $order_ids)->paginate(50);
        // return view('common.order.test-report', compact('orders'));
        return view('common.order.report');
    }

    private function order_reports($datatable){
        return $datatable
        ->addIndexColumn()
        ->editColumn('date_time', function ($row) {
            return date('m/d/Y h:i', strtotime($row->created_at));
        })
        ->editColumn('order_no', function($row){
            if($row->order_id !=null){
                return $row->order->invoice_id;
            }else return $row->id.' does not have order';
            
        })
        ->editColumn('customer', function($row){
            if($row->order_id !=null){
                return $row->order->first_name.' '.$row->order->last_name;
            }else return $row->id.' does not have order';
            
        })
        ->editColumn('phone', function($row){
            if($row->order_id !=null){
                return $row->order->phone;
            }else return $row->id.' does not have order';
        })
        ->editColumn('billing', function($row){
            $data = '';
            if($row->order_id !=null){

                $data = 'Name: '.$row->order->first_name.' '.$row->order->last_name.', Phone:'.$row->order->phone.', ';
                $data .=  ', Distict: '.$row->order->district;
                $data .= ', City: '.$row->order->city; 
                $data .= ', Address:'.$row->order->address;
                return $data;
            }else return $row->id.' does not have order';
        })
        ->editColumn('shipping', function($row){
            $data = '';
            if($row->order_id !=null){
                
                $data .= 'Name: '.$row->order->ship_first_name.' '.$row->order->ship_last_name;
                $data .= ', Phone: '.$row->order->ship_phone.', Email: '.$row->order->ship_email;
                $data .=  ', Distict: '.$row->order->ship_district;
                $data .= ', City: '.$row->order->ship_city; 
                $data .= ', Address:'.$row->order->address;
            
                return $data;
            }else return $row->id.' does not have order';
        })
        ->editColumn('category', function($row){
            if($row->order_id !=null){
                return DB::table('products')->where('id',$row->product_id)->pluck('title')->first();
            }else return $row->id.' does not have order';
        })
        ->editColumn('design_code', function($row){
            return DB::table('products')->where('id',$row->product_id)->pluck('design_code')->first();
        })
        ->editColumn('barcode', function($row){
            if($row->order_id !=null){
                return DB::table('product_combinations')->where('id',$row->product_combination_id)->pluck('barcode')->first();
            }else return $row->id.' does not have order';
        })
        ->editColumn('qty', function($row){
            if($row->order_id !=null){
                return $row->qty;
            }else return $row->id.' does not have order';
        })
        ->editColumn('price', function($row){
            if($row->order_id !=null){
                return number_format($row->sale_price, 2);
            }else return $row->id.' does not have order';
        })
        ->editColumn('disc', function($row){
           
            if($row->promotion_id==null){
                if($row->outlet_percent!=null) $dis_data = $row->outlet_percent;
                else $dis_data =  0;

                if($row->order->invoice_discount > 0){
                    $dis_data = (($row->sale_price - $row->discount_price) / $row->sale_price ) * 100;
                }
                
            }else{
                $pP = DB::table('product_promotion')->where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
              
                if($pP==null){
                    return 'product: '.$row->product_id;
                }else{
                    if($pP->discount_in=='percent'){
                        $dis_data = $pP->discount_value;
                    }else $dis_data = $pP->discount_value;
                }
            }
            return $dis_data.'%';
           
        })
        ->editColumn('disc_name', function($row){
            if($row->order_id !=null){
                if($row->promotion_id==null){
                    if($row->outlet_customer_id!=null){
                        $promoName = 'Outlet Discount';
                    }else $promoName = '';
                }else{
                    $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
                    if($pP==null){
                        $promoName = '';
                    }else{
                        $promoName = DB::table('promotions')->where('id',$row->promotion_id)->pluck('title')->first();
                    }
                }
                return $promoName;
            }else return $row->id.' does not have order';
        })
        ->editColumn('disc_amt', function($row){

            if($row->promotion_id==null){
                if($row->outlet_customer_id!=null){
                    $discountPercent = $row->outlet_percent;
                }else $discountPercent = 0;
            }
            else{
                $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
               
                if($pP==null){
                    $discountPercent = 0;
                }else{
                    if($pP->discount_in=='percent') $discountPercent = $pP->discount_value;
                    else $discountPercent = ($pP->discount_value / 100) * $row->sale_price;
                }
            } 
            $amount = ($row->sale_price/100)*$discountPercent;
            return number_format($amount, 2);
        })
        ->editColumn('tax', function($row){
            if($row->order_id !=null){
                return $row->vat;
            }else return $row->id.' does not have order';
        })
        ->editColumn('tax_line', function($row){
            if($row->order_id !=null){
                return number_format(($row->vat / 100) * $row->sale_price , 2);
            }else return $row->id.' does not have order';
        })
        
        ->editColumn('del_charge', function($row){
            if($row->order_id !=null){
                if($row->order->zone_id !=null) return number_format($row->order->zone->delivery_cost, 2);
            }else return $row->id.' does not have order';
        })
        
        ->editColumn('net_amount', function($row){
            if($row->promotion_id==null){
                if($row->outlet_customer_id!=null){
                    $discountPercent = $row->outlet_percent;
                }else $discountPercent = 0;
            }
            else{
                $pP = \App\Models\Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
               
                if($pP==null){
                    $discountPercent = 0;
                }else{
                    if($pP->discount_in=='percent') $discountPercent = $pP->discount_value;
                    else $discountPercent = ($pP->discount_value / 100) * $row->sale_price;
                }
            } 
            $amount = ($row->sale_price/100)*$discountPercent;
         
            if($row->order_id !=null){
                return number_format($row->net_price - $amount, 2) ;
            }else return $row->id.' does not have order';
        })
        
        ->editColumn('payment_method', function($row){
            if($row->order_id !=null){
                return $row->order->payment_geteway->name;
            }else return $row->id.' does not have order';
        })
        
        ->editColumn('order_status', function($row){
            if($row->order_id !=null){
                return $row->order->order_status->title;
            }else return $row->id.' does not have order';
        })
        ->editColumn('payment_status', function($row){
            if($row->order_id !=null){
                $paymentSum = $row->order->order_payments()->sum('amount');
                if($paymentSum >= $row->order->total_cost){
                    $data = 'Full paid';
                }else $data = 'Pending';
                return $data;
            }else return $row->id.' does not have order';
        })
        ->editColumn('courier', function($row){
            if($row->order_id !=null){
                $courier_order_bundle_id = DB::table('courier_company_orders')->where(['order_id'=>$row->order_id])->pluck('courier_order_bundle_id')->first();
                if($courier_order_bundle_id !=null){
                    $courier_order = Courier_order_bundle::where('id',$courier_order_bundle_id)->first();
                    return $courier_order->courier_company->name;
                }
            }else return $row->id.' does not have order';
        })
        
        ->editColumn('del_date', function($row){
            if($row->order_id !=null){
                
            }else return $row->id.' does not have order';
            
        })
        ->editColumn('transaction_id', function($row){
            return $row->order->transaction_id;
        })
        
        ->editColumn('customer_id', function($row){
            if($row->order_id !=null){
                return $row->order->customer_id;
            }else return $row->id.' does not have order';
        })
    
        ->rawColumns(['date_time','order_no','transaction_id','customer','phone','billing','shipping','category','design_code','barcode','qty','price','disc','disc_name','disc_amt',
        'tax','tax_line','del_charge','net_amount','payment_method','order_status','payment_status','courier','del_date','customer_id'])->make(true);
    }

    function order_excel(Request $request, Excel $excel){
        // return Excel::download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        // return $excel->download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('m-Y').'.xlsx');
        return $excel->download(new OrderExport($request->start_date, $request->end_date), 'order-'.date('d-m-Y').'.xlsx');
    }

    function order_pdf(Request $request){
        if($request->start_date){
            $start   = date('Y-m-d',strtotime(str_replace('-','/',$request->start_date)));
            $end     = date('Y-m-d',strtotime(str_replace('-','/',$request->end_date)));
            $order_ids = Order::whereBetween('order_date', [$start, $end])->select('id')->get()->toArray();
          
            $orders =Order_item::whereIn('order_id', $order_ids)->orderBy('created_at', 'DESC')->get();
        }else{
            $orders= Order_item::orderBy('created_at', 'DESC')->get();
        }

        // return view('common.export.order', ['orders'=>$orders]);
        
        $pdf = PDF::setOption(['defaultFont' => 'sans-serif'])
        ->loadView('common.export.order', ['orders'=>$orders])
        ->setPaper('a1', 'landscape');

        return $pdf->download('order-'.date('d-m-Y').'.pdf');
    }



    function order_durations(Request $request){
        if($request->start_date){
           
        }else{
            
        }

        $averageTime  = DB::select("SELECT LEFT( CONVERT( created_at, TIME), 2) AS hour, LEFT( CONVERT( created_at, TIME), 2)+1 AS hourTo, COUNT(LEFT( CONVERT( created_at, TIME), 2)) as qty, LEFT( CONVERT( created_at, DATETIME), 16) AS dateFrom, LEFT( CONVERT( created_at, DATETIME), 16) as dateTo FROM orders GROUP BY hour,hourTo ORDER BY qty DESC");
        // dd($averageTime);
        return view('common.order.order_durations', compact('averageTime'));
    }

    function duration_orders($hour){
       
        $orders  = DB::select("SELECT LEFT( CONVERT( created_at, TIME), 2) AS mm,invoice_id,total_items FROM orders where LEFT( CONVERT( created_at, TIME), 2)='".$hour."' GROUP BY mm,invoice_id,total_items ORDER BY invoice_id DESC");
        // $orders  = Order::select("SELECT LEFT( CONVERT( created_at, TIME), 2) AS mm,invoice_id,total_items FROM where LEFT( CONVERT( created_at, TIME), 2)='".$hour."' GROUP BY mm,invoice_id,total_items ORDER BY invoice_id DESC");
      
        return view('common.order.duration_orders',compact('orders'));
    }

    function last_week_orders(Request $request){

        if($request->start && $request->end){
            $startDate = Carbon::parse($request->start);
            $endDate = Carbon::parse($request->end); 

            $orders = Order::select( 
                DB::raw('order_date as date'), DB::raw('COUNT(*) as count'),
                // DB::raw('sum(qty) as qtys')
            )->whereBetween('order_date', [date('Y-m-d',strtotime($request->start)), date('Y-m-d',strtotime($request->end))])->groupBy('date')->get();

        }else{
            $lastWeek = Carbon::now()->subWeek();
            $orders = Order::select( 
                // 'order_date',
                DB::raw('order_date as date'),
                DB::raw('COUNT(*) as count'),
            )->where('order_date', '>=', $lastWeek)->groupBy('date')->get();
        }
    
    

       $status = Order_status::all();
        return view('common.order.last-week',compact('orders','status'));
    }

    function date_orders($date){
        $orders = Order::whereDate('order_date',$date)->orderBy('order_status_id')->get();
        return view('common.order.date-order',compact('orders','date'));
    }

    function date_status_orders($date,$status_id){
        $status = Order_status::where('id',$status_id)->first();
        $orders = Order::whereDate('order_date',$date)->where('order_status_id',$status_id)->orderBy('order_status_id')->get();
        return view('common.order.date-order-status',compact('orders','date','status'));
    }




    function monthly_orders (Request $request, Courier_company $courier_company){
   
        if($request->to_month && $request->to_year && $request->from_month && $request->from_year){
            $to = $request->to_year.'-'.$request->to_month;
            $firstDay = $request->from_year.'-'.$request->from_month.'-01';

            $lastDay =  date('Y-m-t', strtotime("$to-01"));


            $dateFrom = strtotime($firstDay);
            $dateTo = strtotime($lastDay);

            $orders = Order::select( 
                DB::raw('MONTH(order_date) as month'),
                DB::raw('YEAR(order_date) as year'),
                DB::raw('COUNT(*) as count')
            )->whereBetween('order_date', [$firstDay, $lastDay])
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->get();

        }else{
            $startDate = now()->subMonths(12)->startOfMonth();
            $endDate = now();

            $orders = Order::select(
                DB::raw('MONTH(order_date) as month'),
                DB::raw('YEAR(order_date) as year'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('order_date', [$startDate, $endDate])
                ->groupBy('year', 'month')
                ->orderBy('year', 'asc')
                ->get();
        }
       $status = Order_status::all();

        return view('common.order.monthly-orders',compact('orders','status'));
    }

}
