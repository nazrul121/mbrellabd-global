
<?php 
    $invoice_logo =  \App\Models\General_info::where('field','invoice_logo')->pluck('value')->first();
    $outlet_discount = array();
    $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
    $binNo = DB::table('general_infos')->where('field','bin')->pluck('value')->first();
    $mushok = DB::table('general_infos')->where('field','mushak')->pluck('value')->first();

    $paidAmount = [];
?>

@php
    $metas = \DB::table('metas')->where('pageFor','invoice');
    $meta = \DB::table('metas')->where(['pageFor'=>'invoice', 'type'=>'title']);

    $metaTitle = 'Print invoice - '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $metas->pluck('description')->first();
    }
@endphp


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{$metaTitle}}</title>
    @push('meta')
        @foreach ($metas->get() as $meta)
            <meta type="{{$meta->title}}" content="{{$meta->description}}">
        @endforeach
    @endpush
</head>
<body>
    <div class="container">
        @if($order)
        <?php $payments = \App\Models\Order_payment::where('transaction_id',$order->transaction_id)->whereIn('status', ['ACCEPTED','VALID','SUCCESS']);
            $totalVat = $subtotal =  array(); $invoice_discount = 0;

            $totalInLocal = $order->total_cost;
            $shippingInLocal = $order->shipping_cost;

            $total = $totalInUSD = $shippingInUSD = 0;

            $orderInDollar = DB::table('dollar_rate_order')->where('order_id',$order->id)->first();
            if($orderInDollar !=null && $shippingInLocal >0 && $totalInLocal>0){
                $shippingInUSD = number_format( $shippingInLocal /$orderInDollar->value , 2);
                $totalInUSD = number_format($totalInLocal/$orderInDollar->value , 2);
            }
        ?>
        <div id="printableArea" style="width:595px;margin:0px auto;">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
            <style>body { font-family: serif;} .table{font-size:11px} .proHead{background:#fbf3e2;}</style>

            <div class="card">
                <div class="row invoice-contact">
                    <div class="col-md-12">
                        <div class="invoice-box row">
                            <div class="col-sm-12 text-capitalize">
                                <table class="table table-responsive invoice-table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width:60%"> <br>
                                                <span>{{ request()->get('system_title') }}</span><br>
                                                {{ request()->get('office_address') }} <br>
                                                {{ request()->get('system_phone') }} <br>
                                                <span class="text-lowercase">{{ request()->get('system_email') }}</span>
                                            </td>
                                            <td style="width:40%;"><a href="{{ route('home') }}"><img src="{{ url('storage').'/'.$invoice_logo }}" alt=""></a><br>
                                                <b> Order Date: {{ date('F j, Y h:ia',strtotime($order->created_at)) }}<br>
                                                Invoice Number #{{$order->invoice_id}} <br>
                                                    BIN No: {{$binNo}} &nbsp; (Mushak-{{$mushok}} ) </b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="width:60%">
                                                <h6>Billing Information --</h6>
                                                Name: {{ $order->first_name.' '.$order->last_name }} <br>
                                                Area: {{ $order->division }} <i class="fa fa-long-arrow-right"></i>  

                                                {{ $order->district }} <i class="fa fa-long-arrow-right"></i>

                                                {{ $order->city }} <br>

                                                Address: {{ $order->address }} <br>
                                                Phone: {{ $order->phone }} <br>
                                                @if($order->email !=null)
                                                    Email:  <span class="text-lowercase">{{ $order->email }} </span>
                                                @endif
                                            </td>

                                            <td style="width:40%;">
                                                <h6>Shipping Information --</h6>
                                                Name: {{ $order->ship_first_name.' '.$order->ship_last_name }} <br>

                                                Area: {{ $order->ship_division }} <i class="fa fa-long-arrow-right"></i> 
                                                {{ $order->ship_district }} <i class="fa fa-long-arrow-right"></i> 
                                                {{ $order->ship_city }} <br> 

                                                Address: {{ $order->ship_address }} <br>
                                                Phone: {{ $order->ship_phone }} <br>
                                              
                                                Email:  <span class="text-lowercase">{{ $order->ship_email }} </span>
                                                
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-bordered m-0" style="">
                                    <thead class="proHead" style="vertical-align:middle;">
                                        <tr>
                                            <td colspan="2">Product info</td>
                                            <td class="text-center">Qty</td>
                                            <td class="text-center">Unit Price</td>
                                            <td class="text-center">VAT %</td>
                                            <td class="text-center">Vat Amount</td>
                                            <td style="text-align:right">Total</td>
                                        </tr>
                                    </thead>
                            
                                        @foreach ($order->order_items()->where('status','!=','removed')->get() as $order_item)
                                            <?php
                                                if($order_item->vat_type=='excluding'){
                                                    $vatExcl = ($order_item->vat / 100) * $order_item->discount_price;
                                                    $vatAmount = ($order_item->vat / 100) * $order_item->discount_price;
                                                }else{
                                                    $vatAmount = ($order_item->vat * ($order_item->discount_price * $order_item->qty) ) / ($order_item->vat + 100);
                                                    $vatExcl = 0;
                                                }
                                                $totalVat[] = $vatExcl;

                                                if($order_item->variation_option_id !=null){
                                                    $thumb = \DB::table('variation_option_photos')->where(['variation_id'=>$listViewVariationId,'product_id'=>$order_item->product_id,'variation_option_id'=>$order_item->variation_option_id])->pluck('thumbs')->first();
                                                    if($thumb==null){
                                                        $thumbs = $order_item->product->thumbs;
                                                    }else $thumbs = $thumb;
                                                }else $thumbs = $order_item->product->thumbs;


                                                if($order_item->outlet_customer_id !=null){
                                                    $outlet_discount[] = ($order_item->outlet_percent / 100) * product_price($order_item->product_id, $order_item->sale_price) * $order_item->qty;
                                                }else $outlet_discount[] = 0;

                                                $disPercent = ((($order_item->sale_price - $order_item->discount_price) / $order_item->sale_price) * 100);
                                            ?>
                                            <tr>
                                                <td style="width:8%" > <img src="{{ $thumbs }}" height="35" data-product="{{$order_item->product_id}}"> {{$order_item->outlet_percent}}</td>
                                                <td style="border-left:0;width:37%">
                                                    <h5 style="font-size:11px">{{ $order_item->product->title }} - {{$order_item->product->design_code }}</h5>
                                                    <p style="font-size: 9px;margin-bottom:0">
                                                        @if($order_item->product_combination_id !=null)
                                                            @foreach ($order_item->product_combination()->get() as $key => $pComb)
                                                                @foreach (explode('~',$pComb->combination_string) as $string)
                                                                    <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                                    <b> {{ $v->variation->title}}: </b> {{ $v->title }}
                                                                @endforeach
                                                            @endforeach
                                                        @endif
                                                    </p>
                                                </td>
                                                <td style="width:5%" class="text-center">{{ $order_item->qty }}</td>

                                                <td class="text-center" style="width:15%">
                                                    @if($order_item->sale_price > $order_item->discount_price)
                                                    <b style="text-decoration:line-through!important;"><span>{{ $order->country->currencySymbol }}</span> {{ round($order_item->sale_price, 2) }}
                                                        : {{round($disPercent, 2)}}%
                                                    </b> 
                                                    @endif
                                                    <span>{{ $order->country->currencySymbol }}</span>{{ round($order_item->discount_price, 2) }}
                                                </td>

                                                <td class="text-center" style="width:11%">
                                                    {{ round($order_item->vat,2) }}%(<small>@if($order_item->vat_type=='excluding')Ex. @else Inc @endif</small>)
                                                </td>
                                                <td style="width:12%;text-align:right"><span>{{ $order->country->currencySymbol }}</span> {{ round($vatAmount,2) }}</td>
                                                <td style="width:15%;text-align:right"><span>{{ $order->country->currencySymbol }}</span> {{ round(($order_item->discount_price * $order_item->qty)+$vatExcl, 2) }}</td>
                                            </tr>
                                            <?php $subtotal[] = round(($order_item->discount_price * $order_item->qty)+$vatExcl, 2);?>
                                        @endforeach
                                </table>
                            
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:right;width:80%">Sub Total </td>
                                            <td style="text-align:right"><span>{{ $order->country->currencySymbol }}</span> {{ array_sum($subtotal) }}</td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right">Shipping charge </td>
                                            <td style="text-align: right"><span>{{ $order->country->currencySymbol }}</span> {{ round($order->shipping_cost , 2) }}</td>
                                        </tr>
                                        @if ($order->country_id !=2)
                                            <tr>
                                                <td style="text-align:right">Shipping charge in USD</td>
                                                <td style="text-align: right"><span>$</span> {{ $shippingInUSD }}</td>
                                            </tr>
                                        @endif 

                                        @if($order->invoice_discount >0)
                                            @php
                                                $invoice_discount_id = DB::table('invoice_discount_order')->where('order_id',$order->id)->pluck('invoice_discount_id')->first();
                                                $invoiceDis = DB::table('invoice_discounts')->where('id',$invoice_discount_id)->first();
                                            @endphp
                                            <tr>
                                                <td style="text-align:right">Invoice Discount ({{ $invoiceDis->type }})</td>
                                                <td style="text-align: right"><span>{{ $order->country->currencySymbol }}</span> {{ round($order->invoice_discount , 2) }}</td>
                                            </tr>
                                        @endif      
                                    
                                        @if(array_sum($outlet_discount) >0)
                                            <tr>
                                                <td style="text-align:right">Membership Discount</td>
                                                <td style="text-align: right"><span>{{ $order->country->currencySymbol }}</span> {{ round(array_sum($outlet_discount) , 2) }}</td>
                                            </tr>
                                        @endif

                                        <tr style="font-weight: bold">
                                            <td style="text-align:right"> Grand Total : </td>
                                            <td style="text-align:right">
                                            <span> {{ $order->country->currencySymbol }}</span> {{ round( ((array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount) -array_sum($outlet_discount), 2) }}
                                            </td>
                                        </tr>
                                        @if ($order->country_id !=2)
                                            <tr style="font-weight: bold">
                                                <td style="text-align:right"> Total In USD: </td>
                                                <td style="text-align:right">
                                                <span> $</span> {{ $totalInUSD + $shippingInUSD }}
                                                </td>
                                            </tr>
                                        @endif

                                        @if($payments->count() > 0)
                                            <tr>
                                                <td>
                                                    @foreach($payments->get() as $payment)
                                                        <?php
                                                            if(strtolower($payment->status)=='success' || strtolower($payment->status)=='accepted' || strtolower($payment->status)=='valid'){
                                                                if($order->country_id != 2) $paidAmount[] = $payment->amount / $order->country->currencyValue;
                                                                else $paidAmount[] = $payment->amount;
                                                         }  ?> 
                                                        <span class="text-secondary"> Paid Amount: <span>{{ $order->country->currencySymbol.$payment->amount }} 
                                                            on <small>{{ date('M d,y h:ia',strtotime($payment->created_at)) }} via
                                                                @if($payment->payment_type_id==null) Not taken @else {{ $order->payment_geteway->name }} 
                                                                @endif </small> (Transaction: {{ $payment->status }}) </span> <br>
                                                        </span>
                                                    @endforeach
                                                </td>
                                                <td style="text-align:right">
                                                    Paid Amount: <span>{{ $order->country->currencySymbol }}{{ number_format(array_sum($paidAmount), 2) }}</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Due</td>
                                                <td style="text-align:right">
                                                    <span>{{ $order->country->currencySymbol }}{{ number_format( ( ( (array_sum($subtotal) + $order->shipping_cost) - array_sum($paidAmount) ) ) - $order->invoice_discount, 2) }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        @if($order->note !=null)<tr> <td colspan="3"> Order Notes : {{ $order->note }} </td></tr> @endif
                                    </tbody>
                                </table>

                                <table class="table table-bordered">
                                    <tr><td>Payment info</td>  <td>Invoice & Transaction info</td> </tr>
                                    <tr>
                                        <td style="width:60%">

                                            @if($payments->count() < 1)
                                                Payment Method: {!! $order->payment_geteway->name !!} <br>
                                                Payment Status: <span>Unpaid</span> <br>
                                                Total Due :  <span> {{ $order->country->currencySymbol }}</span> {{ round( ((array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount) - array_sum($outlet_discount), 2) }}
                                            @else
                                                Payment Method: {!! $order->payment_geteway->name !!} <br>
                                                Total Amount :  <span> {{ $order->country->currencySymbol }}</span> {{ number_format(array_sum($subtotal) + $order->shipping_cost, 2) }} <br>
                                                @foreach($payments->get() as $payment)
                                               
                                                    <span class="text-secondary"> Paid Amount: <span>{{ $order->country->currencySymbol.' '.$payment->amount }} on <small>{{ date('M d,y h:ia',strtotime($payment->created_at)) }} via @if($payment->payment_type_id==null) Not taken @else {{ $order->payment_geteway->name }} @endif </small> (Transaction: {{ $payment->status }}) </span> <br>
                                                        
                                                    </span>
                                                @endforeach
                                                @php
                                                    
                                                @endphp
                                                
                                                Total Due: <span>{{ $order->country->currencySymbol }}
                                                    {{ number_format((  (((array_sum($subtotal) + $order->shipping_cost ) - array_sum($paidAmount)) - $order->invoice_discount)) - array_sum($outlet_discount) , 2) }}</span>
                                            @endif
                                        </td>
                                        <td style="width: 40%">
                                        Invoice Number: #{{$order->invoice_id}} <br>Transaction ID: #{{ $order->transaction_id }}
                                        </td>

                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
          
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-12 invoice-btn-group text-center">
                <br><br>
                <button onclick="printableArea()" class="btn btn-info">Print Invoice</button>
                <button onclick="history.back();"class="btn btn-secondary m-b-10 ">Back</button>
            </div>
        </div>
        @else
            <p class="alert alert-danger">Something went wrong. Please contact to the authority</p>
        @endif
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <script>
        function printableArea() {
            const printContents = document.getElementById('printableArea').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

</body>
</html>