
@extends('customer.layouts')

@section('title', 'My orders')

@section('content')
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-left">
                <thead  class="bg-light">
                    <tr>
                        <th>#</th><th>Date</th><th>Transaction ID</th>
                        <th>Pricing</th>
                        <th class="text-right">More</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $key=>$order)
                    <tr>
                        <td>{{ $key+1 }}</td>
                        <td>{{ date('M d, y H;ia',strtotime($order->created_at)) }}</td>
                        <td>{{ $order->transaction_id }}</td>
                        <td>{{ $order->country->currencySymbol}}
                            @if ($order->shippingCostFrom=='zone')
                                {{ $order->total_cost + $order->zone->delivery_cost}}
                            @endif
                        </td>
                        <td class="text-right">
                                <div class="collection-sorting position-relative">
                                    <b class="float-end"> ....</b>
                                    <ul class="sorting-lists list-unstyled m-0">
                                        <li class="more" data-id="{{ $order->id }}"><a href="javaScript:;"> <i class="fa fa-print text-info"></i> More</button></a></li>
                                        <li class="payment" data-id="{{ $order->id }}"><a href="javaScript:;"><i class="fas fa-money-check-alt text-warning"></i> Payment info</a></li>

                                        <li><a href="/print-invoice/{{ $order->transaction_id }}" target="_blank"> <i class="fa fa-print text-success"></i> Invoice</a></li>
                                        <li><a href="/truck?invoice={{ $order->transaction_id }}" target="_blank"> <i class="fa fa-truck text-secondary"></i> Track</a></li>
                                    </ul>
                                </div>
                            {{-- <a href="/print-invoice/{{ $order->transaction_id }}" target="_blank"> <i class="fa fa-print text-success"></i> Invoice</a>
                            <a href="javaScript:;" class="more" data-id="{{ $order->id }}"> <i class="fa fa-print text-info"></i> More</button></a>
                            <a href="/truck?invoice={{ $order->transaction_id }}" target="_blank"> <i class="fa fa-truck text-secondary"></i> Track</a>
                            <a href="javaScript:;" class="payment" data-id="{{ $order->id }}"><i class="fas fa-money-check-alt text-warning"></i> Payments</a> --}}
                        </td>
                    </tr>
                    <tr class="details order{{ $order->id }}" style="display:none;">
                        <td colspan="7">
                            <table class="table bg-5 table-bordered text-left">
                                <tr>
                                    <th>Product Info</th>
                                    <th>Unit Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                                @foreach ($order->order_items()->get() as $key=>$orderItem)
                                    <?php if($orderItem->product_variant_id !=null){
                                        $thumb = \DB::table('color_product')->where(['product_id'=>$orderItem->product_id,'color_id'=>$orderItem->product_variant->color_id])->pluck('thumbs')->first();
                                    }else $thumb = $orderItem->product->thumbs;?>

                                    <tr>
                                        <td><img src="{{ $thumb }}" style="width:30px;float:left"> &nbsp;  {{ $orderItem->product->title }} <br>
                                            <small>@if($orderItem->product_variant_id !=null)
                                                <?php  $pv = \App\Models\Product_variant::where('id',$orderItem->product_variant_id)->first()->toArray();
                                                unset($pv['id']);unset($pv['product_id']); unset($pv['barcode']);unset($pv['qty']); unset($pv['created_at']);unset($pv['updated_at']); ?>
                                                @foreach ($pv as $key => $value)
                                                    @if(!empty($value))
                                                        <?php $vt = \App\Models\Variant_table::where('fk_id',$key)->first();?>
                                                    &nbsp; <b class="text-capitalize">{{ str_replace('_id','',$key)}}: </b> {{ $vt->model::find($value)->title }},
                                                    @endif
                                                @endforeach
                                            @endif</small>
                                        </td>
                                        <td>{{ $order->country->currencySymbol }} {{ $orderItem->product->sale_price * $order->country->currencyValue }}</td>
                                        <td> {{ $orderItem->qty }}</td>
                                        <th>{{ $orderItem->product->sale_price * $order->country->currencyValue * $orderItem->qty; }}</th>
                                        <?php $total[] = $orderItem->product->sale_price * $order->country->currencyValue * $orderItem->qty; ?>
                                    </tr>
                                @endforeach
                            </table>
                        </td>
                    </tr>
                    <tr class="bg-5 payment{{ $order->id }}" style="display:none;">
                        <td colspan="7">
                            <table class="table table-bordered text-left">
                                <tr>
                                    <th>Payment via</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                </tr>
                                @foreach ($order->order_payments()->get() as $key=>$payment)
                                    <tr>
                                        <td>{{ $payment->payment_type->title }}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->status }}</td>
                                        <td>{{ date('M d, y, H:ia',strtotime($payment->created_at)) }}</td>
                                    </tr>
                                @endforeach
                                @if($order->order_payments()->get()->count() <1)
                                    <tr class="text-center text-danger"><td colspan="4">No payment found</td></tr>
                                @endif
                            </table>
                        </td>
                    </tr>
                    @endforeach

                    @if($orders->count()<1)
                        <tr>
                            <td colspan="5" class="text-center text-danger"> No match found</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
        <div class="row">
            <div class="col-12">
                <ul class="text-center mt-4">
                    {{$orders->links() }}
                </ul>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('.more').on('click',function(){
               let id = $(this).data('id');
               $( '.order'+id).toggle('slow');
            })

            $('.payment').on('click',function(){
               let id = $(this).data('id');
               $( '.payment'+id).toggle('slow');
            })
        })
    </script>
@endpush
