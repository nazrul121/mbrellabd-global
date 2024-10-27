@extends('layouts.app')

@section('content')

<?php 
    $metas = \DB::table('metas')->where('pageFor','order-placed');
    $meta = \DB::table('metas')->where(['pageFor'=>'order-placed', 'type'=>'title']);

    $metaTitle = 'Mbrella | Order placed';
    if($meta->count() >0){
        $metaTitle = $metas->pluck('description')->first();
    }

    $validStatuses = ['ACCEPTED', 'VALID', 'SUCCESS'];

    // dd($payment->status, in_array($payment->status, $validStatuses));
?>

@section('title',$metaTitle)

@push('meta')
    @foreach ($metas->get() as $meta)
        <meta type="{{$meta->title}}" content="{{$meta->description}}">
    @endforeach
@endpush


<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" />
                    </g>
                </svg>
            </li>
            <li><a href="{{url('checkout')}}">Checkout</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"  xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Order Placed</li>
        </ul>
    </div>
</div>
<?php $subtotal = $vats = array();

    $totalInLocal = $order->total_cost;
    $shippingInLocal = $order->shipping_cost;
    $total = $totalInUSD = $shippingInUSD = 0;

    $orderInDollar = DB::table('dollar_rate_order')->where('order_id',$order->id)->first();
    if($orderInDollar !=null && $shippingInLocal >0 && $totalInLocal>0){
        $shippingInUSD = number_format( $shippingInLocal /$orderInDollar->value , 2);
        $totalInUSD = number_format($totalInLocal/$orderInDollar->value , 2);
    }

?>


<div class="wishlist-page mt-100">
    <div class="wishlist-page-inner">
        <div class="container">
            {{-- <div class="section-header d-flex align-items-center justify-content-between flex-wrap">
                <h4>Transaction ID#{{$order->transaction_id}}</h4>
            </div>
            --}}
            <div class="row">
                <div class="col-lg-12 col-md-12 col-12 aos-init aos-animate mb-5" data-aos="fade-up" data-aos-duration="700">
                  
                    <div class="bg-white mt-3 p-3" style="border:1px solid silver">
                        @if($payment==null)
                            <p class="alert alert-warning  p-3"><i class="fa fa-info-circle fa-lg"></i> You don`t a successful payment <span class="text-info">but we took your order.</span></p>
                        @else
                            @if(in_array($payment->status, $validStatuses)==false)
                              <p class="alert alert-warning  p-3"><i class="fa fa-info-circle fa-lg"></i> You don`t a successful payment <span class="text-info">but we took your order.</span></p>
                            @endif
                        @endif
                        <p class="alert alert-info p-3"> <i class="fa fa-check-square fa-lg"></i> Thank you. Your order has been placed successfully.</p>

                     
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Order Number </th>
                                    <th>Order Date </th>
                                    <th>Order Total </th>
                                    <th>Payment method </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>{{ $order->transaction_id }} </td>
                                    <td>{{ date('F j, Y g:ma',strtotime($order->created_at)) }} </td>
                                    <td>{{ $order->country->currencySymbol }}{{ $order->total_cost + $order->shipping_cost}}</td>
                                    <td>{!! $order->payment_geteway->name !!}</td>
                                </tr>
                                <tr>
                                    <td colspan="4"> {!! $order->payment_geteway->description !!} </td>
                                </tr>
                            </tbody>
                        </table>
                   

                        <table class="table table-info bg-5 mb-0">
                            <h3 class="pt-3" >Order details</h3>
                            <table class="table table-hover bg-white">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->order_items()->get() as $order_item)
                                        <?php if($order_item->vat_type=='excluding'){
                                            $vat = ($order_item->vat / 100) *  $order_item->discount_price * $order_item->qty;
                                        }else $vat = null; ?>
                                        <tr>
                                            <td>
                                                <img height="40" src="{{ $order_item->product->thumbs }}" alt="img">
                                                <a href="{{ route('product',[app()->getLocale(), $order_item->product->slug]) }}" target="_blank">{{ $order_item->product->title }}</a>
                                                <small>
                                                    @if($order_item->product_combination_id !=null)
                                                        @foreach ($order_item->product_combination()->get() as $key => $pComb)
                                                            @foreach (explode('~',$pComb->combination_string) as $string)
                                                                <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                                <b class="p-2"> {{ $v->variation->title.': '.$v->title }} </b>
                                                            @endforeach
                                                        @endforeach
                                                    @endif
                                                </small>
                                                <strong> {{ $order->country->currencySymbol }} {{ $order_item->discount_price }}</strong>
                                                <strong >×&nbsp;{{ $order_item->qty }}</strong>
                                        	</td>
                                            <td class="text-end">
                                                {{ $order->country->currencySymbol }}</span> {{ $order_item->discount_price * $order_item->qty }}
                                        	</td>
                                        </tr>
                                        <?php $subtotal[] = $order_item->discount_price * $order_item->qty;
                                            $vats[] = $vat * $order_item->qty;
                                        ?>
                                    @endforeach
                                </tbody>

                                <tfoot class="text-end">
                                    <tr>
                                        <th>Subtotal:</th>
                                        <td>{{ $order->country->currencySymbol }}</span> {{ array_sum($subtotal) }}</span></td>
                                    </tr>
                                    <tr>
                                        <td>Shipping:
                                            @if ($order->country_id ==2)
                                                @if($order->zone==null) <span class="text-warning">not defined</span>
                                                @else {{ $order->zone->title }}  @endif
                                            @else
                                                {{ $order->shippingCostFrom }}
                                            @endif
                                        </td>
                                        <td>{{ $order->country->currencySymbol .' '. $order->shipping_cost }}</td>
                                    </tr>
                                    @if($order->invoice_discount >0)
                                    <tr>
                                        <td>Invoice Discount</td>
                                        <td>{{ $order->country->currencySymbol .' '. round($order->invoice_discount , 2) }}</td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th scope="row">Total:</th>
                                        <td>{{ $order->country->currencySymbol.' '.round( (array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount, 2)}}</td>
                                    </tr>
                                    @if ($order->country_id !=2)
                                        <tr style="font-weight: bold">
                                            <td> Total In USD: </td>
                                            <td> <span> $</span> {{ $totalInUSD + $shippingInUSD }} </td>
                                        </tr>
                                    @endif

                                    @if($order->note !=null)
                                    <tr>
                                        <th>Note:</th>
                                        <td>{{ $order->note }}</td>
                                    </tr> @endif
                                </tfoot>
                            </table>

                        </section>

                        <a href="{{ url('print-invoice').'/'.$order->transaction_id }}" class="mt-2 p-2 btn-warning float-end"><i class="fa fa-print"></i> &nbsp; Print invoice</a>
                        
                    </div>
                
                </div>
                
            </div>
        </div>
    </div>            
</div>


@endsection
