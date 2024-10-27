
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <div id="printOrders">
                    <table class="table table-bordered">
                        <style>
                            body { color: #000; }
                            .table thead th { border-bottom: 1px solid #000000; }
                            .table td, .table th {  padding: 2px; }
                        </style>
                        <thead>
                            <tr>
                                <th class="text-center" colspan="5">
                                    <img src="/storage/{{request()->get('header_logo')}}" alt="">
                                    <h6>HOUSE 19, ROAD 03, SECTOR 03, UTTARA, DHAKA-1230, BANGLADESH.</h6>
                                    <h5><b>Order Progress Report</b></h5>
                                    <h6>Report Date: {{date('d/m/Y',strtotime($date))}}</h6>
                                    <p class="text-left">Store : Ecommerce 
                                        &nbsp; &nbsp; &nsc; <b>Status:{{$status->title}}</b>
                                        <b class="float-right">Print date: {{date('d/m/Y H:i')}}</b>
                                    </p>
                                </th>
                            </tr>
                            <tr>
                                <th>#</th>
                                <th>Invoice</th>
                                <th>Order items ({{$orders->count()}})</th>
                                <th>Customer info</th>
                                <th class="text-right p-2">Order value</th>
                            </tr>
                            <?php $totalPrices = $qtys = []; ?>
                            <tbody class="p-2">
                                <style>
                                    body { color: #000; }
                                    .table thead th { border-bottom: 1px solid #000000; }
                                    .table td, .table th {  padding: 2px; }
                                </style>
                                @foreach($orders as $key0=>$order)
                                <?php $prices = $qty =  []; ?>
                                <tr>
                                    <td>{{$key0+1}}</td>
                                    <td>{{$order->invoice_id}}</td>
                                    <td>
                                        @if($order->order_items()->count()>0)
                                            <ul>
                                                @foreach($order->order_items()->get() as $key=>$oi) 
                                                    <li class="p-2">{{$key+1}}. {{$oi->product->title}} ({{$oi->discount_price}} x {{$oi->qty}} = {{$oi->discount_price * $oi->qty}})</li>
                                                    <?php 
                                                        $prices[] = $oi->discount_price * $oi->qty;
                                                        $qty[] = $oi->qty;
                                                    ?>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </td>
                                    <td class="text-left p-2">{{$order->customer->first_name}} {{$order->customer->last_name}} <br>
                                        @if($order->customer->phone !=null) {{$order->customer->phone}} <br> @endif
                                        {{$order->customer->address}}
                                    </td>
                                    <td class="text-right p-2">{{array_sum($prices)}}</td>
                                   
                                    <?php 
                                        $totalPrices[] = array_sum($prices); 
                                        $qtys[] = array_sum($qty); 
                                    
                                    ?>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="p-2 bg-light">
                                <tr>
                                    <td><b>{{$orders->count()}}</b></td>
                                    <td colspan="4">
                                        <p class="p-2 text-center">Order quantities: <b>{{array_sum($qtys)}}</b></p>
                                    </td>
                                    <td class="text-right p-2"><b>{{array_sum($totalPrices)}}</b></td>
                                </tr>
                            </tfoot>
                        </thead>
                    </table>

                    <table class="table table-bordered">
                        <tr>
                            <td class="text-center" style="width:25%"> <br><br>
                                {{Auth::user()->email}} <hr style="border:1px solid;">   Prepared by
                            </td>
                            <td style="vertical-align: bottom;"> </td>
                        </tr>

                    </table>
                </div>

                <div class="col-12 text-center">
                    <button onclick="printOrderItems()" class="btn btn-info"><i class="fa fa-print"></i> Print Report</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('scripts')
    
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css">
    <script src="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('back2') }}/js/pages/ac-datepicker.js"></script>
    <script src="{{ asset('back2') }}/js/timepicker.js"></script>
    <style>
        .datepicker>.datepicker-days { display: block;}
        ol.linenums {  margin: 0 0 0 -8px;}
    </style>


    <script src="{{ asset('back2')}}/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('back2')}}/js/pages/ac-datepicker.js"></script>

    <script>
        function printOrderItems() {
            const printContents = document.getElementById('printOrders').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }        
    </script>

@endpush
