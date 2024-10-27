
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5><b>{{ $order_status->title }}</b>  dataTable</h5>
                <button class="btn btn-sm btn-secondary readyBtn" disabled>Ready to Ship</button>
            </div>

            <div class="card-body">
                @if($orders->count()>0)
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="width:100%">
                            <thead>
                                <tr><th> @if($order_status->id=='3')<input type="checkbox" id="checkAll" style="width:25px;height:25px"> @else # @endif </th>
                                    <th>Transaction ID</th>
                                    <th>Product info</th>
                                    <th>Customer info</th>
                                    <th>Order info</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key=>$order) <?php $rand = rand(22,22);?>
                                <tr>
                                    <th class="ready2ship">
                                        @if($order_status->id=='3')
                                        <input type="checkbox" class="selectedOrder" name="order_ids[]" style="width:25px;height:25px" value="{{ $order->id }}"> @else {{ $key+1 }}
                                        @endif
                                    </th>
                                    <td>
                                        Transaction ID: {{ $order->transaction_id }} <br>
                                        <a class="btn btn-sm btn-primary pb-0 pt-0"  href="{{ route('common.order.invoice',$order->id) }}" target="_blank"><i class="fa fa-print"></i> View Invoice</a> <br>

                                        Status: {{ $order->order_status->title }} <br>
                                        <button class="btn btn-sm btn-info pb-0 pt-0 deliveryPorcess" data-id="{{ $order->id }}" type="button"><i class="fa fa-truck"></i> Delivery process</button> <br>
                                        <button class="btn btn-sm btn-warning pb-0 pt-0 payment" data-transaction_id="{{ $order->transaction_id }}" data-id="{{ $order->id }}" type="button"><i class="fas fa-money-bill-alt"></i> Payment</button>

                                    </td>
                                    <td class="text-capitalize">
                                        <p class="bg-light p-2 text-center">Total of <b>{{ $order->order_items()->get()->count() }}</b> order items</p>

                                        @foreach ($order->order_items()->get() as $key1=>$order_item)
                                        <a data-toggle="collapse" href="#collapse{{ $order_item->product_id + $order->invoice_id+$key1}}" role="button" aria-expanded="false" aria-controls="collapse{{ $order_item->product_id + $order->invoice_id+$key1}}"><h6 class="p-1">{{ $key1+1}}. {{ $order_item->product->title }}</h6> </a>
                                            @if($order_item->product_combination_id !=null)
                                                @foreach ($order_item->product_combination()->get() as $key2 => $pComb)
                                                <div class="collapse" id="collapse{{ $order_item->product_id + $order->invoice_id+$key1}}">
                                                    <ul class="ml-2">
                                                    @foreach (explode('~',$pComb->combination_string) as $string)
                                                        <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                        <li> {{ $v->variation->title}}: </b> {{ $v->title }}</li>
                                                    @endforeach
                                                    </ul>
                                                </div>
                                                @endforeach
                                            @endif
                                        @endforeach
                                    </td>
                                    <td> <b class="text-capitalize cusInfo{{ $order->transaction_id }}">{{ $order->customer->first_name.' '.$order->customer->last_name }} ({{ $order->customer->user->phone }})</b> <br>
                                        @if($order->customer->division)
                                            Area: {{ $order->customer->division->name }} <i class="fa fa-arrow-right"></i> @endif
                                        @if($order->customer->district)
                                            {{ $order->customer->district->name }} <i class="fa fa-arrow-right"></i>  @endif
                                        @if($order->customer->city)
                                            {{ $order->customer->city->name }} <br> @endif

                                        <p style="white-space: break-spaces;"><b>Address:</b> {{ $order->customer->address }}</p>
                                        @if($order->note !=null)
                                            <br> <button class="btn btn-sm btn-secondary pb-0 pt-0 note" data-transaction_id="{{ $order->transaction_id }}" title="{{ $order->note }}" type="button"><i class="fa fa-user"></i> Customer Note</button>
                                        @endif
                                        <button  class="btn btn-sm btn-secondary pb-0 pt-0 shippingInfo" data-transaction_id="{{ $order->transaction_id }}" data-id="{{ $order->shipping_address_id }}" type="button"><i class="fa fa-map-marker-alt"></i> Shipping address</button> <br>
                                    </td>
                                    <td>
                                        Date: {{ date('d F Y',strtotime($order->created_at)) }} <br>
                                        {{ $order->payment_geteway->name }} <br>
                                        Order in: {{ $order->currency->name }} <br>
                                        Order value: {{ $order->currency->symbol }} {{ $order->total_cost}} <br>
                                        Shipping charge: {{ $order->currency->symbol }} {{ $order->shipping_cost }} <br>
                                        Total amount:  {{ $order->total_cost + $order->shipping_cost }} <br>

                                    </td>
                                </tr>
                                @endforeach
                                @if($orders->count() <1)
                                    <tr>
                                        <td colspan="5" class="text-center text-danger">No <b>{{ $order_status->title }}</b> found</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                    {{ $orders->links() }}
                @else
                <p class="p-4 bg-light text-info text-center">No order found</p>
                @endif
            </div>

        </div>
    </div>
</div>


@include('common.order.modal')

@endsection


@push('scripts')

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $('.note').on('click',function(){
            let transaction_id = $(this).data('transaction_id');
            $('#orderModal').modal('show');
            let customer = $('.cusInfo'+transaction_id).text();
            $('#orderModalLabel').text(customer);
            $('.modalData').html($(this).attr('title'));
        })

        $('.shippingInfo').on('click',function(){
            $('#orderModal').modal('show');
            let id = $(this).data('id');
            let transaction = $(this).data('transaction_id');
            let customer = $('.cusInfo'+transaction).text();
            $('#orderModalLabel').text(customer);
            $.get( url+"/common/order/shipping-address/"+id, function(data, status){
                $('.modalData').html(data);
            });
        })

        $('.deliveryPorcess').on('click',function(){
            $('#deliveryModal').modal('show');
            let id = $(this).data('id');
            $.get( url+"/common/order/delivery-process/"+id, function(data, status){
                $('.delivery_result').html(data);
            });
        })

        $('.payment').on('click',function(){
            $('#paymentModal').modal('show');
            let transaction_id = $(this).data('transaction_id');
            let customer = $('.cusInfo'+transaction_id).text();
            $('#paymentModalLabel').text(customer);
            let id = $(this).data('id');
            $('#paymentForm').attr('action','/common/order/create-payment/'+id);
            $.get( url+"/common/order/ask-for-payment/"+id, function(data, status){
                $('.paymentResult').html(data);
            });
        })


        var clicked = false;
        $("#checkAll").on("click", function() {
            $("input.selectedOrder").prop("checked", !clicked);
            clicked = !clicked;
            this.innerHTML = clicked ? 'Deselect' : 'Select';

            if($('input.selectedOrder').is(':checked')){
                $('.readyBtn').prop('disabled',false);
            }else $('.readyBtn').prop('disabled',true);
        });

        $('.ready2ship').on('change',function(e){
            if($('input.selectedOrder').is(':checked')){
                $('.readyBtn').prop('disabled',false);
            }else $('.readyBtn').prop('disabled',true);
        })

        $('.readyBtn').on('click',function(event){
            var searchIDs = $(".ready2ship input:checkbox:checked").map(function(){
                return $(this).val();
            }).toArray();
            $('#shipModal').modal('show');
            // console.log(searchIDs);
            $.get( url+"/common/order/prepare-to-ship/"+searchIDs, function(data, status){
                $('.ready2Ship').html(data);
            });
        });

    });
</script>

@endpush
