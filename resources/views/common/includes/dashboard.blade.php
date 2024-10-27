
@extends('superAdmin.layouts')

@section('title',' Dashboard | Report board')
@section('content')
@php

    $totalOrder = \App\Models\Order::count();
    $todayOrders = App\Models\Order::whereDate('created_at', \Carbon\Carbon::today())->orderBy('created_at','desc')->get();
    $orderStatuses = App\Models\Order_status::withCount('orders')
        ->orderBy('orders_count', 'desc')->get();

    // $weeklyOrders = App\Models\Order::thisWeek()->get();
    
    $dailyAverage = \App\Models\Order::selectRaw('COUNT(*) / COUNT(DISTINCT order_date) as average_orders_per_day')
        ->value('average_orders_per_day');

@endphp
<div class="row">
    @foreach ($orderStatuses as $status)
        @php
            if($status->orders()->count() !=0 ){
                $statusOrder = $status->orders()->count() / $totalOrder;
            }else $statusOrder = 0;

            $retioNum = round($statusOrder * 100, 2);
        @endphp
        {{-- @if($retioNum >0) --}}
        <div class="col-md-4 col-xl-3">
            <div class="card Online-Order">
                <div class="card-block">
                    <a href="{{ route('common.orders',$status->id) }}" >
                        <h5>{{$status->title}}</h5>
                        
                        <h6 class="text-muted d-flex align-items-center justify-content-between m-t-30">Order ratio<span class="float-right f-18 
                            @if(strpos(strtolower($status->title), 'deliver') !== false) text-success @endif
                            @if(strpos(strtolower($status->title), 'cancel') !== false || strpos(strtolower($status->title), 'return') !== false) text-danger @endif
                            @if(strpos(strtolower($status->title), 'refund') !== false) text-warning @endif
                            ">{{ $status->orders()->count() }} <b class="text-dark">of</b> {{ $totalOrder }}</span></h6>
                        <div class="progress mt-3">
                            <div class="progress-bar @if(strpos(strtolower($status->title), 'deliver') !== false) progress-c-theme @endif
                                @if(strpos(strtolower($status->title), 'cancel') !== false ||  strpos(strtolower($status->title), 'return') !== false) progress-c-red @endif
                                @if(strpos(strtolower($status->title), 'refund') !== false) progress-c-yellow @endif
                            " role="progressbar" style="width:{{ $retioNum }}%;height:6px;" aria-valuenow="{{ $retioNum }}" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    
                        <span class="text-muted mt-2 d-block"> <b>{{ $retioNum }}</b> % {{ str_replace('order', ' ',strtolower($status->title)) }}</span>
                    </a>
                </div>
            </div>
        </div>
        {{-- @endif --}}
    @endforeach
</div>


<div class="row">
    <div class="col-xl-12 col-md-12">
        <div class="card Recent-Users">
            <div class="card-header">
                <h5>Today Orders</h5>
                <div class="card-header-right">
                    <div class="btn-group card-option">
                        <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="feather icon-more-horizontal"></i> </button>

                        <ul class="list-unstyled card-option dropdown-menu dropdown-menu-right">
                            <li class="dropdown-item full-card"><a href="#!"><span><i class="feather icon-maximize"></i> maximize</span><span style="display:none"><i class="feather icon-minimize"></i> Restore</span></a></li>
                            <li class="dropdown-item minimize-card"><a href="#!"><span><i class="feather icon-minus"></i> collapse</span><span style="display:none"><i class="feather icon-plus"></i> expand</span></a></li>
                            <li class="dropdown-item reload-card"><a href="#!"><i class="feather icon-refresh-cw"></i> reload</a></li>
                            <li class="dropdown-item close-card"><a href="#!"><i class="feather icon-trash"></i> remove</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-block px-0 py-3">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr><th>#</th> <th>IDs</th> <th>Product info</th> <th>Customer info</th> <th>Payment</th></tr>
                        </thead>
                        <tbody>
                            @foreach ($todayOrders as $key=>$order)
                            <tr> <th>{{ $key+1 }}</th>
                                <td>
                                    Transaction ID: {{ $order->transaction_id }} <br>
                                    Invoice ID: {{ $order->invoice_id }} <br>
                                    Order Status: {{ $order->order_status->title }} <br>
                                    <a class="btn btn-sm btn-secondary pb-0 pt-0"  href="{{ route('common.order.invoice',$order->id) }}" target="_blank"><i class="fa fa-print"></i> View Invoice</a> <br>

                                </td>
                                <td class="text-capitalize">
                                    @foreach($order->order_items()->get() as $item)
                                        @if($item->product !=null)
                                            {{ $item->product->title }} <br>

                                            @if($item->product_variant_id !=null)
                                                <?php $pv = \App\Models\Product_variant::where('id',$item->product_variant_id)->first()->toArray();
                                                unset($pv['id']);unset($pv['product_id']); unset($pv['barcode']);unset($pv['qty']); unset($pv['created_at']);unset($pv['updated_at']); ?>
                                                <ul class="ml-2">
                                                    @foreach ($pv as $key => $value)
                                                        @if(!empty($value))
                                                        <?php $vt = \App\Models\Variant_table::where('fk_id',$key)->first(); ?>
                                                        <li>{{ str_replace('_id','',$key).': '.$vt->model::find($value)->title }}</li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        @else 
                                            <p class="text-center text-danger">Order Item ID: <b>{{ $item->id }}</b> relate the <b>Product ID</b> that does not exist!</p>
                                        @endif 
                                    @endforeach
                                </td>
                                <td>{{ $order->first_name.' '.$order->last_name }} <br>
                                    Phone: {{ $order->phone }} @if($order->email !=null) <br> Email: {{ $order->email }} @endif <br>
                                    Area: {{ $order->division }} <i class="fa fa-arrow-right"></i>
                                     {{ $order->district }} <i class="fa fa-arrow-right"></i> 
                                     {{ $order->city }} <br> 
                                    Address: {{ $order->address }}
                                </td>
                                <td>
                                    Date: {{ date('d F Y',strtotime($order->created_at)) }} <br>
                                    {{ $order->payment_geteway->name }} <br>
                                    Order in: {{ $order->country->name }} <br>
                                    Order value: {{ $order->country->currencySymbol }} {{ $order->total_cost}} <br>
                                    Shipping charge: {{ $order->country->currencySymbol }} {{ $order->shipping_cost }} <br>
                                    Total amount:  {{ $order->total_cost + $order->shipping_cost }} <br>

                                </td>
                            </tr>
                            @endforeach
                            @if($todayOrders->count() <1)
                                <tr>
                                    <td colspan="5" class="text-center text-danger">No <b>order</b> found <b>Today</b></td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
	<!--begin::Page Scripts -->
    <script src="{{ asset('back2') }}/js/pages/dashboard-custom.js"></script>

@endpush
