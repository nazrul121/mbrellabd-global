
@extends('common.layouts')

@section('content')
    <?php $allStatus = $statusCount = $totalOrders = $totalQtys = $totalCost = $allDeliverdPrice = array(); 

        if(request()->get('from_month') && request()->get('to_month')){
            $to = request()->to_year.'-'.request()->to_month;
            $startDate = request()->from_year.'-'.request()->from_month.'-01';
    
            $endDate =  date('Y-m-t', strtotime("$to-01"));
    
        }else{
            $startDate = now()->subMonths(12)->startOfMonth();
            $endDate = now();
        }
        
    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>
                        @if(request()->get('start') && request()->get('end'))
                            <b>{{date('d/m/Y',strtotime($startDate))}}</b> to <b>{{date('d/m/Y',strtotime($endDate))}}</b>
                        @else Last 12 month orders @endif
                        dataTable</h5> &nbsp; &nbsp; &nbsp; &nbsp; 

                        <div class="form-group d-inline">
                            <div class="radio radio-success d-inline">
                                <input type="radio" name="radio-s-in-1" id="dateBase">
                                <label for="dateBase" class="cr">Date wize report</label>
                            </div>
                        </div>
                        <div class="form-group d-inline">
                            <div class="radio radio-success d-inline">
                                <input type="radio" name="radio-s-in-1" id="monthBase" checked="">
                                <label for="monthBase" class="cr">Month Wize report</label>
                            </div>
                        </div>

                    <div class="card-header-right">
                        <form class="input-daterange input-group">@csrf
                            <b class="p-2">from</b>
                            <select name="from_month" >
                                @foreach(months() as $data=>$yer)
                                    <option @if(request()->get('from_month')==$data)selected @endif value="{{$data}}">{{$yer}}</option>
                                @endforeach
                           </select> 
                           <select name="from_year" >
                                @foreach(years() as $data=>$yer)
                                    <option @if(request()->get('from_year')==$yer)selected @endif value="{{$yer}}">{{$yer}}</option>
                                @endforeach
                           </select>
                          
                           <b class="p-2">to</b>

                           <select name="to_month" >
                                @foreach(months() as $data=>$yer)
                                    <option @if(request()->get('to_month')==$data)selected @endif value="{{$data}}">{{$yer}}</option>
                                @endforeach
                           </select>
                           <select name="to_year" >
                                @foreach(years() as $data=>$yer)
                                    <option @if(request()->get('to_year')==$yer)selected @endif value="{{$yer}}">{{$yer}}</option>
                                @endforeach
                           </select>
                           
                            <button type="submit" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> Prepare</button>
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div id="printableArea">
                        <style>
                            body { color: #000; }
                            .table thead th { border-bottom: 1px solid #000000; }
                            .table td, .table th {  padding: 2px; }
                        </style>
                        <table class="table bg-white table-hover table-bordered orderTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th colspan="{{$status->count() + 5}}" class="text-center">
                                        <img src="/storage/{{request()->get('header_logo')}}" alt="">
                                        <h6>HOUSE 19, ROAD 03, SECTOR 03, UTTARA, DHAKA-1230, BANGLADESH.</h6>
                                        <h5><b>Order Progress Report</b></h5>
                                        <h6>Report Date: From  {{date('d/m/Y',strtotime($startDate))}} To {{date('d/m/Y',strtotime($endDate))}} </h6>
                                        <p class="text-left">Store : Ecommerce 
                                            <b class="float-right">Print date: {{date('d/m/Y H:i')}}</b>
                                        </p>
                                    </th>
                                </tr>
                                <tr class="text-center">
                                    <th>Month</th>
                                    <th>Number <br> of Orders</th>
                                    <th>Order Qty</th>
                                    <th>Order Value</th>

                                    @foreach($status as $sta)
                                        <?php  
                                            $firstSpacePosition = strpos($sta->title, ' ');
                                            if ($firstSpacePosition !== false) {
                                                $part1 = substr($sta->title, 0, $firstSpacePosition + 1); 
                                                $part2 = substr($sta->title, $firstSpacePosition + 1);
                                                $title = $part1 . '<br/>' . $part2;
                                            } 
                                            else $title =  $sta->title;                                            
                                        ?>
                                        <th>{!! $title !!}</th>
                                        @if($sta->relational_activity=='delivered')
                                            <th class="text-success">Deliverd Value</th>
                                        @endif
                                    @endforeach
                                    
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($orders as $key=>$order)
                                    <?php 
                                        $startDate = now()->setYear($order->year)->setMonth($order->month)->startOfMonth();
                                        $endDate = now()->setYear($order->year)->setMonth($order->month)->endOfMonth();
                            
                                        $qtyCount = DB::table('order_items')->whereBetween('created_at', [$startDate, $endDate])->sum('qty');
                                        $qtyPrice = DB::table('order_items')->whereBetween('created_at', [$startDate, $endDate])
                                            ->selectRaw('SUM(qty * discount_price) as total_cost')
                                            ->value('total_cost');
                                        
                                        $totalCost [] =$qtyPrice;
                                        $totalQtys[] = $qtyCount;
                                        $totalOrders[] =$order->count;

                                    ?>
                                    <tr class="text-center">
                                        <td class="showOrder text-left" data-date="{{$order->date}}">
                                            {{date("F", strtotime("2023-$order->month-01"))}}, {{ $order->year}} 
                                            <!-- <br> {{date('d/m/Y',strtotime($startDate))}} - {{date('d/m/Y',strtotime($endDate))}} -->
                                        </td>
                                        <td class="showOrder" data-date="{{$order->date}}">{{$order->count}}</td>
                                        <td class="showOrder" data-date="{{$order->date}}">
                                            {{$qtyCount}}
                                        </td>
                                        <td class="showOrder" data-date="{{$order->date}}">{{$qtyPrice}}</td>
                                        @foreach($status as $sta)
                                            <?php 
                                                $count = DB::table('orders')->whereBetween('order_date', [$startDate, $endDate])->where('order_status_id',$sta->id)->count();
                                                $allStatus[] = $sta->title; $statusCount[] = $count;
                                                $deliveredPrice = 0;

                                                if($sta->relational_activity=='delivered'){
                                                    $orderIDs =  DB::table('orders')->whereBetween('order_date', [$startDate, $endDate])->where('order_status_id',$sta->id)
                                                        ->select('id')->get()->toArray();
                                                    
                                                    $ids = [];
                                                    foreach ($orderIDs as $item) $ids[] = $item->id;
                                                    
                                                    $deliveredPrice = DB::table('order_items')->whereBetween('created_at', [$startDate, $endDate])
                                                        ->whereIn('order_id', $ids)
                                                        ->selectRaw('SUM(qty * discount_price) as total_cost')
                                                        ->value('total_cost');
                                                    $allDeliverdPrice[] = $deliveredPrice ;
                                                   
                                                }
                                            ?>
                                            <td data-status_id="{{$sta->id}}" data-date="{{$order->date}}" class="showOrderStatus @if(str_contains( $sta->title, 'shipped') && $count>0)text-danger @endif"> {{$count}} </td>
                                            @if($sta->relational_activity=='delivered')
                                                <th> @if($deliveredPrice>0){{ $deliveredPrice }} @else 0 @endif</th>
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                @php
                                // Combine the two arrays into an associative array
                                $combinedData = [];
                                foreach ($allStatus as $key => $value) {
                                    if (!isset($combinedData[$value])) {
                                        $combinedData[$value] = 0;
                                    }
                                    $combinedData[$value] += $statusCount[$key];
                                }
                                @endphp
                            </tbody>
                            <tfooter>
                                <tr class="bg-light text-dark text-center" style="font-size:19px">
                                    <td><b>Total:</b></td>
                                    <td><b>{{array_sum( $totalOrders )}}</b></td>
                                    <td><b>{{array_sum( $totalQtys )}}</b></td>
                                    <td><b>{{array_sum($totalCost)}}</b></td>
                                    @foreach ($combinedData as $data => $sum)
                                        <td><b>{{ $sum }}</b> </td>
                                        @if($data=='Order Delivered')
                                            <td class="text-success"><b>{{ array_sum($allDeliverdPrice) }}</b> </td>
                                        @endif
                                    @endforeach
                                </tr>
                            </tfooter>
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
                </div>
                <div class="card-footer">
                    <div class="row text-center">
                        <div class="col-sm-12 invoice-btn-group text-center">
                            <button onclick="printableArea()" class="btn btn-info"><i class="fa fa-print"></i> Print Report</button>
                        </div>
                    </div>
                </div>
                
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    
    <script>
        function printableArea() {
            const printContents = document.getElementById('printableArea').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }

        $('.showOrderT').on('click', function(){
            $('.view_orders').html('Working...');
            var date = $(this).data('date');
            $('.orders-modal-title').text('Orders of '+date);
            window.open('/common/report/date-orders/'+date)
            // $('#ordersModel').modal('show');
            // $.get('/common/report/date-orders/'+date, function(data){
            //     $('.view_orders').html(data);
            // })
        })
        $('.showOrderStatusT').on('click', function(){
            $('.view_orders').html('Working...');
            var date = $(this).data('date');
            var status_id = $(this).data('status_id');
            $('.orders-modal-title').text('Orders of '+date);
            window.open('/common/report/date-status-orders/'+date+'/'+status_id)
            // $('#ordersModel').modal('show');
            // $.get('/common/report/date-status-orders/'+date+'/'+status_id, function(data){
            //     $('.view_orders').html(data);
            // })
        })

        $('#dateBase').on('change', function(){
            window.location.href = "/common/report/last-week-orders";
        })

        
        
    </script>

@endpush