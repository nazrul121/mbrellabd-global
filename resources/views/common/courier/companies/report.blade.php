
@extends('common.layouts')

<?php 
 $statusIDs = $allStatus = $allZone= $totalReturnCost = [];

 $firstDate = request()->get('start');
 $lastDate = request()->get('end');
 
 if($bundles !=null){
    foreach($bundles as $key=>$bundle){
        
        if($key==0) $lastDate = $bundle->created_at;
        if ($key === $bundles->count() - 1) $firstDate = $bundle->created_at;
       
        $cco = DB::table('courier_company_orders')->where('courier_order_bundle_id',$bundle->id)->select('order_id');
        foreach($cco->get() as $courierOrder){
            $statusIDs[] = \DB::table('orders')->where('id',$courierOrder->order_id)->select('order_status_id')->pluck('order_status_id')->first();
        }
    }
    $statusIDs = array_unique($statusIDs,SORT_REGULAR);
    // $status = \App\Models\Order_status::whereIn('id',$statusIDs)->get();
    $status = \App\Models\Order_status::orderBy('sort_by')->get();
    $totalOrders = $totalQtys = $totalPrices = $totalDelivered = $totalReturned = $totalZoneCost = array();

    $runningSum = 0;
 
    foreach($courier_company->courier_zones()->get() as $key=>$zone){
        $array[$zone->id] = array();
        $arrayReturn[$zone->id] = array();
    }
 }
 
 $courires = DB::table('courier_companies')->orderBy('name')->get();

?>
@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <form class="card-header" id="searchForm" action="?">@csrf
                <h5>Order transfer for: &nbsp; 
                    <select id="companyList">
                        <option value="">Select Courier Company</option>
                        @foreach($courires as $company)
                            <option @if($courier_company !=null && $company->id==$courier_company->id)selected @endif value="{{ $company->id }}">{{$company->name}}</option>
                        @endforeach
                    </select>
                </h5> &nbsp; &nbsp;
                <div class="form-group d-inline">
                    <div class="radio radio-success d-inline">
                        <input type="radio" name="durationType" id="dateBase" checked="" value="date">
                        <label for="dateBase" class="cr">Date wize report</label>
                    </div>
                </div>
                <div class="form-group d-inline">
                    <div class="radio radio-success d-inline">
                        <input type="radio" name="durationType" id="monthBase" value="month">
                        <label for="monthBase" class="cr">Month Wize report</label>
                    </div>
                </div>
                <div class="card-header-right">
                    <div class="input-daterange input-group">
                        <input type="date" class="form-control text-left" placeholder="Start date" name="start" value="{{ request()->get('start') }}">
                        <input type="date" class="form-control text-right" placeholder="End date" name="end" value="{{ request()->get('end') }}">
                        <button type="submit" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> Prepare</button>
                    </div>
                </div>
            </form>

            <div class="card-body">
                <div id="printableArea">
                    <style>
                        body { color: #000; }
                        .table thead th { border-bottom: 1px solid #000000; }
                        .table td, .table th {  padding: 2px; }
                    </style>
                    @if($bundles !=null)
                    <div class="responsive-table">
                        <table class="table table-hover table-bordered bg-white text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center" colspan="{{$status->count() + $courier_company->courier_zones()->count() + $courier_company->courier_zones()->count() + 8}}">
                                        <img src="/storage/{{request()->get('header_logo')}}" alt="">
                                        <h6>HOUSE 19, ROAD 03, SECTOR 03, UTTARA, DHAKA-1230, BANGLADESH.</h6>
                                        <h5><b>Courier order Report</b> (date wise)</h5>
                                        <h6>Report Date: From  <b>@if(request()->get('start')) {{date('d/m/Y',strtotime(request()->get('start')))}} 
                                            @else {{ date('d/m/Y',strtotime($firstDate)) }} @endif</b> to <b>
                                            
                                            @if(request()->get('end')) {{date('d/m/Y',strtotime(request()->get('end')))}} 
                                            @else {{ date('d/m/Y',strtotime($lastDate)) }} @endif </b> </h6>
                                        <p class="text-left">Courier Company: {{$courier_company->name}} 
                                        <b class="float-right">Print date: {{date('d/m/Y h:ia')}}</b>
                                        </p>
                                    </th>
                                </tr>
                                <tr>
                                    <th class="text-left">#</th>
                                    <th class="text-left">Date</th> 
                                    <th class="text-left">Bundle </th>
                                    <th>Number <br> of Orders </th> 
                                    <th>Orders <br> Value</th>
                                    <th>Order <br> Qty</th>
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
                                        <th> 
                                            @if (strpos(strtolower($title), 'return') !== false) <span class="text-danger">{!! $title !!}</span>
                                            @elseif(strpos(strtolower($title), 'deliver') !== false) <span class="text-success">{!! $title !!}</span>
                                            @elseif(strpos(strtolower($title), 'shipped') !== false) <span class="text-warning">{!! $title !!}</span>
                                            @else {!! $title !!} @endif
                                        </th>
                                        @if($sta->relational_activity=='delivered')
                                            <th class="text-success">Delivered <br> Value</th>
                                            @foreach($courier_company->courier_zones()->get() as $zone)
                                            <?php 
                                                $firstSpacePosition = strpos($zone->name, ' ');
                                                if ($firstSpacePosition !== false) {
                                                    $part1 = substr($zone->name, 0, $firstSpacePosition + 1); 
                                                    $part2 = substr($zone->name, $firstSpacePosition + 1);
                                                    $title = $part1 . '<br/>' . $part2;
                                                } 
                                                else $title =  $sta->title;       
                                            ?>
                                            <th>DLV {!!$title!!}: 
                                                <!-- {{$zone->delivery_cost}} -->
                                            </th>
                                            @endforeach
                                        @endif

                                        @if($sta->relational_activity=='return')
                                            @foreach($courier_company->courier_zones()->get() as $zone)
                                            <?php 
                                                $firstSpacePosition = strpos($zone->name, ' ');
                                                if ($firstSpacePosition !== false) {
                                                    $part1 = substr($zone->name, 0, $firstSpacePosition + 1); 
                                                    $part2 = substr($zone->name, $firstSpacePosition + 1);
                                                    $title = $part1 . '<br/>' . $part2;
                                                } 
                                                else $title =  $sta->title;       
                                            ?>
                                            <th>RTN {!!$title!!}:
                                                <!-- {{$zone->return_cost}} -->
                                            </th>
                                            @endforeach
                                            <th class="text-danger">Return <br> Value</th>
                                        @endif
                                    @endforeach
                                    
                                </tr>
                            </thead>


                            <tbody>
                                @foreach($bundles as $key=>$bundle)
                                <tr class="row_{{$bundle->id}} bunleTr">
                                    <td class="text-left">{{$key+1}}</td>
                                    <td class="text-left">{{date('d/m/Y',strtotime($bundle->created_at))}}</td>
                                    <td class="text-left bundleOrders" data-id="{{$bundle->id}}">{{$bundle->bundle_id}}</td>
                                    <td>
                                        <?php 
                                            $orderIDs =[];
                                            $prices = $qtys = [];
                                            $cco = DB::table('courier_company_orders')->where('courier_order_bundle_id',$bundle->id);
                                            foreach($cco->get() as $courierOrder){
                                                $prices[] = DB::table('order_items')->where('order_id',$courierOrder->order_id) ->selectRaw('SUM(qty * discount_price) as total_cost')
                                                    ->value('total_cost');
                                                $qtys[] = DB::table('order_items')->where('order_id',$courierOrder->order_id) ->selectRaw('SUM(qty) as qtys')
                                                    ->value('qtys');
                                                $orderIDs [] = $courierOrder->order_id;
                                            }

                                            $totalOrders[] = $cco->count();
                                            $totalQtys[] = array_sum($qtys);
                                            $totalPrices[] = array_sum($prices);
                                        ?>
                                        
                                       {{$cco->count()}}
                                    </td>
                                    <td>{{array_sum($prices)}}</td>
                                    <td>{{array_sum($qtys)}}</td>
                                    @foreach($status as $key=>$sta)
                                        <?php 
                                            $orders = \App\Models\Order::where('order_status_id',$sta->id)->whereIn('id',$orderIDs);
                                            $allStatus[] = $sta->title; $statusCount[] = $orders->count();
                                            $deliveredPrice = 0;
                                            $allDeliverdPrice =[];
                                            $loopReturnCost = [];
                                            $deliverdOrders = 0;

                                            if($sta->relational_activity=='delivered'){
                                                foreach($orders->get() as $order){
                                                    $deliverdOrders += \DB::table('orders')->where(['id'=>$order->id, 'order_status_id'=>$sta->id])->count();
                                                    $deliveredPrice = DB::table('order_items')->where('order_id',$order->id)
                                                        ->selectRaw('SUM(qty * discount_price) as total_cost')
                                                        ->value('total_cost');
                                                    $allDeliverdPrice[] = $deliveredPrice + $order->shipping_cost;
                                                }
                                                $totalDelivered[]= array_sum($allDeliverdPrice);
                                                echo '<td>'.$deliverdOrders.'</td>';
                                            }
                                            else{
                                                echo '<td>'.$orders->count() .'</td>';
                                            } 

                                            if($sta->relational_activity=='return'){
                                                foreach($courier_company->courier_zones()->get() as $key=>$zone){
                                                    $courier_company_orders = \App\Models\Courier_company_order::where(['courier_order_bundle_id'=>$bundle->id,'courier_zone_id'=>$zone->id])->get();
                                                    foreach($courier_company_orders as $cco){
                                                        if($cco->order->order_status->relational_activity=='return'){
                                                            $arrayReturn[$zone->id][]=  $cco->return_cost;
                                                            $totalReturnCost[]=  $cco->return_cost;
                                                            $loopReturnCost[]= $cco->return_cost;
                                                        
                                                        }
                                                    }
                                                }
                                            }
                                        ?>
                                        

                                        
                                        @if($sta->relational_activity=='delivered')
                                            <td> @if(array_sum($allDeliverdPrice)>0){{ array_sum($allDeliverdPrice) }} @else 0 @endif</td>

                                            @foreach($courier_company->courier_zones()->get() as $key=>$zone)
                                                <?php  $del_Cost = []; 
                                                 $courier_company_orders = \App\Models\Courier_company_order::where(['courier_order_bundle_id'=>$bundle->id,'courier_zone_id'=>$zone->id])->get();?>
                                                <td>
                                                    @foreach($courier_company_orders as $cco)
                                                        <?php 
                                                        if($cco->order->order_status->relational_activity=='delivered'){
                                                            $array[$zone->id][]=  $cco->delivery_cost;
                                                            $totalZoneCost[]=  $cco->delivery_cost;
                                                            $del_Cost[] = $cco->delivery_cost;
                                                        }else $del_Cost[] =0;
                                                        ?>
                                                    @endforeach
                                                    {{ array_sum($del_Cost) }}
                                                </td>
                                            @endforeach
                                            
                                        @endif
                                        
                                        @if($sta->relational_activity=='return')
                                            @foreach($courier_company->courier_zones()->get() as $key=>$zone)
                                                <?php  $return_cost = []; 
                                                 $courier_company_orders = \App\Models\Courier_company_order::where(['courier_order_bundle_id'=>$bundle->id,'courier_zone_id'=>$zone->id])->get();?>
                                                <td>
                                                    @foreach($courier_company_orders as $cco)
                                                        <?php 
                                                        if($cco->order->order_status->relational_activity=='return'){
                                                            $totalReturned[] = $cco->return_cost;
                                                            $return_cost[] = $cco->return_cost;
                                                        }
                                                        ?>
                                                    @endforeach
                                                    {{ array_sum($return_cost) }}
                                                </td>
                                            @endforeach
                                            <td> {{array_sum($loopReturnCost)}}</td>
                                        @endif
                                        <?php $loopReturnCost = array();?>
                                    @endforeach
                                    
                                </tr>
                                @endforeach

                                @php
                                // Combine the two arrays into an associative array
                                $combinedData = [];
                                $delCostAll = [];
                                foreach ($allStatus as $key => $value) {
                                    if (!isset($combinedData[$value])) {
                                        $combinedData[$value] = 0;
                                    }
                                    $combinedData[$value] += $statusCount[$key];
                                }
                                @endphp
                                <tfoot class="bg-light text-center">
                                    <tr>
                                        <th class="text-left" colspan="3">Total: 
                                            <!-- {{array_sum($totalZoneCost)}} -->
                                        </th>
                                        <th>{{array_sum($totalOrders)}}</th>
                                        <th>{{array_sum($totalPrices)}}</th>

                                        <th>{{array_sum($totalQtys)}}</th>
                                        @foreach ($combinedData as $data => $sum)

                                            <td> <b>{{ $sum }}</b> </td>
                                            @if($data=='Order Delivered')
                                                <td class="text-success"><b>{{ array_sum($totalDelivered) }}</b> </td>
                                              
                                                @foreach($courier_company->courier_zones()->get() as $key=>$zone)
                                                   <td class="text-danger">{{ array_sum($array[$zone->id]) }}</td>
                                                @endforeach
                                            @endif
                                            @if($data=='Order Returned')
                                                @foreach($courier_company->courier_zones()->get() as $key=>$zone)
                                                <td class="text-danger">{{ array_sum($arrayReturn[$zone->id]) }}</td>
                                                @endforeach
                                                <td class="text-danger"><b>{{ array_sum($totalReturned) }}</b> </td>
                                            @endif
                                        @endforeach
                                    </tr>
                                    <tr class="text-left">
                                        <td colspan="{{$status->count() + $courier_company->courier_zones()->count() + $courier_company->courier_zones()->count() + 8}}">
                                           
                                            Total return cost: {{array_sum($totalReturnCost)}}<br>
                                            Total Shipping cost (<b>{{$courier_company->name}}</b>): {{array_sum($totalZoneCost)}} <br>
                                            Total order Delivered Values: {{array_sum($totalDelivered)}} <br/>
                                            So, total payable for <b>{{$courier_company->name}}</b> is: <b>{{ array_sum($totalReturnCost) + array_sum($totalZoneCost) }}</b> <br>
                                            Total Remain amount is : <b>{{ array_sum($totalDelivered) - (array_sum($totalReturnCost) + array_sum($totalZoneCost)) }}</b>
                                        </td>
                                    </tr>
                                </tfoot>
                            </tbody>
                        </table>

                        <table class="table table-bordered">
                            <tr>
                                <td class="text-center" style="width:25%"> <br><br>
                                    {{Auth::user()->email}} <hr style="border:1px solid;">Prepared by
                                </td>
                                <td style="vertical-align: bottom;"> </td>
                            </tr>
                        </table>
                    </div>
                    @else 

                    @endif
                </div>
            </div>
            <div class="card-footer">
                <div class="row text-center">
                    <div class="col-sm-12 invoice-btn-group text-center">
                        @if($bundles !=null)
                        <button onclick="printableArea()" class="btn btn-info"><i class="fa fa-print"></i> Print Report</button> @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="bunOrdModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <form id="addForm" class="modal-content" method="post" enctype="multipart/form-data" action="{{ route('common.couriers.create') }}"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Bundle orders</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body bundle_orders"> </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')

<script>
    function printableArea() {
        const printContents = document.getElementById('printableArea').innerHTML;
        const originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;

        // Add a style tag for print media
        $('head').append('<style type="text/css" media="print"> @page { size: A4 landscape; } </style>');

        // Print the content
        window.print();

        // Restore the original content and remove the print-specific style
        document.body.innerHTML = originalContents;
        $('head style[media="print"]').remove();
    }


    $('#companyList').on('change', function(){
        var id = $(this).val();
        $('#searchForm').attr('action','/common/report/company-report/'+id)
    })

    $('#monthBase').on('change', function(){
        var id = $('#companyList').val();

        if (typeof id === 'undefined' || id === null || id === '') {
            alert('Please choose courier company')
            $("#monthBase").prop("checked", false);
            $("#dateBase").prop("checked", true);
        }else{
            window.location.href = "/common/report/company-monthly-report/"+id;
        }
    })
    

    $('.bundleOrders').on('click', function(){
        $('.bunleTr').css('background','none');
        $('.bundle_orders').html('Working on...');
        var id = $(this).data('id');
        $('#bunOrdModal').modal('show');
        $('.row_'+id).css('background','#f3c9c9')
        $.get('/common/bunle-orders/'+id, function(data){
            $('.bundle_orders').html(data);
        })
    });
</script>
@endpush
