
@extends('common.layouts')

@section('content')
    <?php $allStatus = $statusCount = $totalOrders = $totalQtys = $totalCost = $allDeliverdPrice = array(); 
        
        $currentDate = new \DateTime();

        // Calculate the last date (today)
        $lastDate = clone $currentDate;

        // Calculate the first date of the last 7 days
        $firstDate = clone $currentDate;
        $firstDate->modify('-6 days'); // Subtract 6 days to go back 7 days

        // Format the dates as strings
        $firstDateStr = $firstDate->format('Y-m-d');
        $lastDateStr = $lastDate->format('Y-m-d');

    ?>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>
                        @if(request()->get('start') && request()->get('end'))
                            <b>{{date('d/m/Y',strtotime(request()->get('start')))}}</b> to <b>{{date('d/m/Y',strtotime(request()->get('end')))}}</b>
                        @else Last week orders @endif
                        dataTable</h5> &nbsp; &nbsp; &nbsp; &nbsp; 

                        <div class="form-group d-inline">
                            <div class="radio radio-success d-inline">
                                <input type="radio" name="radio-s-in-1" id="dateBase" checked="">
                                <label for="dateBase" class="cr">Date wize report</label>
                            </div>
                        </div>
                        <div class="form-group d-inline">
                            <div class="radio radio-success d-inline">
                                <input type="radio" name="radio-s-in-1" id="monthBase">
                                <label for="monthBase" class="cr">Month Wize report</label>
                            </div>
                        </div>

                    <div class="card-header-right">
                        <form class="input-daterange input-group">@csrf
                            <input type="date" class="form-control text-left" placeholder="Start date" name="start" value="{{ request()->get('start') }}">
                            <input type="date" class="form-control text-right" placeholder="End date" name="end" value="{{ request()->get('end') }}">
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
                                        <h6>Report Date: From 
                                        @if(request()->get('start') && request()->get('end'))
                                            {{ request()->get('start') }} To {{ request()->get('end') }}
                                        @else 
                                            {{date('d/m/Y',strtotime($firstDateStr))}} To {{date('d/m/Y',strtotime($lastDateStr))}}
                                        @endif</h6>
                                        <p class="text-left">Store : Ecommerce 
                                        <b class="float-right">Print date: {{date('d/m/Y H:i')}}</b>
                                        </p>
                                    </th>
                                </tr>
                                <tr class="text-center">
                                    <th>Date</th>
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
                                        $qtyCount = DB::table('order_items')->whereDate('created_at',$order->date)->sum('qty');
                                        $qtyPrice = DB::table('order_items')->whereDate('created_at',$order->date)
                                            ->selectRaw('SUM(qty * discount_price) as total_cost')
                                            ->value('total_cost');
                                        
                                        $totalCost [] =$qtyPrice;
                                        $totalQtys[] = $qtyCount;
                                        $totalOrders[] =$order->count;
                                    ?>
                                    <tr class="text-center">
                                        <td class="showOrder" data-date="{{$order->date}}">{{date('d/m/Y',strtotime($order->date))}}</td>
                                        <td class="showOrder" data-date="{{$order->date}}">{{$order->count}}</td>
                                        <td class="showOrder" data-date="{{$order->date}}"> {{ $qtyCount}}</td>
                                        <td class="showOrder" data-date="{{$order->date}}">{{$qtyPrice}}</td>
                                        @foreach($status as $sta)
                                            <?php 
                                                $count = DB::table('orders')->whereDate('order_date',$order->date)->where('order_status_id',$sta->id)->count();
                                                $allStatus[] = $sta->title; $statusCount[] = $count;
                                                $deliveredPrice = 0;

                                                if($sta->relational_activity=='delivered'){
                                                    $orderIDs =  DB::table('orders')->whereDate('order_date',$order->date)->where('order_status_id',$sta->id)
                                                        ->select('id')->get()->toArray();
                                                    
                                                    $ids = [];
                                                    foreach ($orderIDs as $item) $ids[] = $item->id;
                                                    
                                                    $deliveredPrice = DB::table('order_items')->whereDate('created_at',$order->date)
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

    <!-- view orders  -->
    <div class="modal fade" id="ordersModel" tabindex="-1" role="dialog" aria-labelledby="ordersLable" aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div id="editForm" class="modal-content">
                <div class="modal-header">
                    <h5 class="orders-modal-title"></h5>
                    <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body view_orders"> </div>
                <div class="modal-footer"> </div>
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

        $('.showOrder').on('click', function(){
            $('.view_orders').html('Working...');
            var date = $(this).data('date');
            $('.orders-modal-title').text('Orders of '+date);
            window.open('/common/report/date-orders/'+date)
            // $('#ordersModel').modal('show');
            // $.get('/common/report/date-orders/'+date, function(data){
            //     $('.view_orders').html(data);
            // })
        })
        $('.showOrderStatus').on('click', function(){
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

        $('#monthBase').on('change', function(){
            window.location.href = "/common/report/monthly-orders";
        })

        
        
    </script>

@endpush