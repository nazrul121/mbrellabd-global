
@extends('common.layouts')

@php
    // $division = \App\Models\Division::orderBy('name')->get();

    $division = \App\Models\Division::leftJoin('customers','divisions.id','=','customers.division_id')
    ->leftJoin('orders','customers.id','=','orders.customer_id')
    ->selectRaw('divisions.*, sum(orders.customer_id) as total')
    ->groupBy('customers.division_id')
    ->orderBy('total','desc')->get();

    if(request()->get('district')){
        $district = \DB::table('districts')->where('id',request()->get('district'))->select('id','name')->first();
        $div = \App\Models\Division::where('id',request()->get('division'))->select('id','name')->first();
        $districts = \DB::table('districts')->where('division_id',$div->id)->get();
    }
    else if(request()->get('division') && request()->get('district')==''){                           
        $div = \App\Models\Division::where('id',request()->get('division'))->select('id','name')->first();
        $district = null;
        $districts = \DB::table('districts')->where('division_id',$div->id)->get();

    }else  $div = $districts = $district = null;

@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mt-2 p-2">
                    <div class="col-md-7">
                        <div class="row">
                            
                            <div class="col-md-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date From</label>
                                    <input type="date" class="form-control col-md-8" placeholder="Start date" name="start_date" value="{{ request()->start }}">
                                </div>
                            </div>
                            
                            <div class="col-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date To</label>
                                    <input type="date" class="form-control col-md-8" placeholder="End date" name="end_date" value="{{ request()->end }}">
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="row">
                                    <button type="button" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> View</button>
                                </div>
                            </div>
                        </div>
                    </div>
              
                    
                    <div class="col-md-5 text-right">
                        <select name="division" class="p-2 border-info bg-light">
                            <option value="">All Divisions</option>
                            @foreach ($division as $item)
                                <option @if($div !=null && $div->id==$item->id)selected @endif value="{{$item->id}}">{{$item->name}}</option>
                            @endforeach
                        </select> &nbsp; 
    
                        <select name="district" class="p-2 border-info bg-light">
                            <option value="">All districts</option>
                            @if($districts !=null)
                                @foreach ($districts as $item)
                                    <option value="{{$item->id}}" @if(request()->get('district')==$item->id)selected @endif>{{$item->name}}</option>
                                @endforeach
                            @else 
                                <option value="">All districts</option>
                            @endif
                        </select>
                    </div>
                </div>

                <div class="card-body">

                    @if(request()->get('start') && request()->get('end'))
                        <p class="pt-4 h5 pl-2 pb-4 bg-info m-0 text-white">
                            <b class="feather icon-calendar"> Data</b> from <b>{{ date('d M, Y',strtotime(request()->get('start'))) }}</b> to <b>{{ date('d M, Y',strtotime(request()->get('end'))) }}</b>
                        </p> @endif 

                    <table class="table bg-white table-hover table-bordered orderTable" style="width:100%">
                        <thead>
                            <tr class="table-active text-white"> <th>Area</th> <th>Orders</th> <th>Customers</th> </tr>
                        </thead>
                        <tbody>
                            @if($div !=null && $div->id==request()->get('division'))
                                <tr style="background-color:#ffc1071f!important">
                                    <td>{{$div->name}} @if($district !=null)<i class="feather icon-arrow-right"></i> {{$district->name}} @endif</td>
                                    <td>
                                        @php
                                            $orders = $div_customer_ids = [];
                                           
                                            if($district ==null || $district ==''){
                                                if(request()->get('start') && request()->get('end')){
                                                    $customers = $div->customers()->where('division_id',$div->id)->whereBetween('created_at', [request()->get('start'), request()->get('end')]);
                                                }else $customers = $div->customers()->where('division_id',$div->id);
                                            }else{
                                                if(request()->get('start') && request()->get('end')){
                                                    $customers = $div->customers()->where('district_id',$district->id)->whereBetween('created_at', [request()->get('start'), request()->get('end')]);
                                                }else $customers = $div->customers()->where('district_id',$district->id);
                                            }
                                            
                                            foreach ($customers->get() as $key => $customer) {
                                                if($customer->orders()->count() >0){
                                                    $orders[] = $customer->orders()->count();
                                                    $div_customer_ids[] = $customer->id;
                                                }
                                            }
                                        @endphp
                                        {{COUNT($orders)}} <small>Orders</small>
                                    <small> <button class="btn btn-sm btn-info float-right orders" data-ids="{{implode(',', $div_customer_ids)}}">Order details</button></small>
                                    </td>
                                    <td>{{COUNT($div_customer_ids)}} <small>Customers</small>
                                        <small><button class="btn p-1 bg-secondary float-right text-white customers" data-ids="{{implode(',', $div_customer_ids)}}">Customer details</button></small>
                                    </td>
                                </tr>

                                @if($district ==null)
                                    @foreach ($districts as $item)
                                        <tr style="background-color:#d8d8d82c!important">
                                            <td>&nbsp; &nbsp; &nbsp; &nbsp; {{$item->name}}</td>
                                            <td>
                                                @php
                                                    $all_dis_orders = $div_customer_ids = [];
                                                    
                                                    if(request()->get('start') && request()->get('end')){
                                                        $all_dis_customers = $div->customers()->where('district_id',$item->id)->whereBetween('created_at', [request()->get('start'), request()->get('end')]);
                                                    }else $all_dis_customers = $div->customers()->where('district_id',$item->id);

                                                    foreach ($all_dis_customers->get() as $key => $customer) {
                                                        if($customer->orders()->count() >0){
                                                            $all_dis_orders[] = $customer->orders()->count();
                                                            $div_customer_ids[] = $customer->id;
                                                        }
                                                    }
                                                @endphp
                                                {{COUNT($all_dis_orders)}} <small>Orders </small>
                                                <small> <button class="btn btn-sm btn-info float-right orders" data-ids="{{implode(',', $div_customer_ids)}}">Order details</button></small>
                                            </td>
                                            <td>{{COUNT($div_customer_ids)}} <small>Customers</small>
                                                <small><button class="btn p-1 bg-secondary float-right text-white customers" data-ids="{{implode(',', $div_customer_ids)}}">Customer details</button></small>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endif
                            
                            @if(!request()->get('division'))     
                                @foreach ($division as $division)
                                <tr>
                                    <td> {{$item->name}} </td>
                                    <td>
                                        @php
                                            $all_orders = $all_customer_ids = [];
                                            if(request()->get('start') && request()->get('end')){
                                                $customers = $division->customers()->whereBetween('created_at', [request()->get('start'), request()->get('end')]);
                                            }else  $customers = $division->customers();
                                            
                                            foreach ($customers->get() as $key => $customer) {
                                               
                                                if($customer->orders()->count() >0){
                                                    $all_orders[] = $customer->orders()->count();
                                                    $all_customer_ids[] = $customer->id;
                                                }
                                            }
                                        @endphp
                                        {{COUNT($all_orders)}} <small>Orders</small>
                                    <small> <button class="btn btn-sm btn-info float-right orders" data-ids="{{implode(',', $all_customer_ids)}}">Order details</button></small>
                                    </td>
                                    <td>{{COUNT($all_customer_ids)}} <small>Customers</small>
                                        <small><button class="btn p-1 bg-secondary float-right text-white customers" data-ids="{{implode(',', $all_customer_ids)}}">Customer details</button></small>
                                    </td>
                                </tr>
                                @endforeach 
                            @endif
                            
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
    @include('common.order.area.modals')

    <input id="flatpickr01" type="text" class="form-control flatpickr-input" data-toggle="flatpickr">
@endsection

@push('style')
<link rel="stylesheet" href="{{ asset('back2') }}/flatpickr.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/theme.min.css">
@endpush

@push('scripts')
<script src="{{ asset('back2') }}/flatpickr.min.js"></script>
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>




<script>
 
    $(function(){
        $('[name=division]').on('change',function(){
            let id = $(this).val();
            $("[name=district]").html('');
            $("[name=district]").append('<option value="">All districts</option>');
            if(id.length >0){
                $.ajax({
                    url:url+"/get-districts/"+id, method:"get",
                    success:function(data){
                        $.each(data, function(index, value){
                            $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                        });
                    }
                });
            }else{
                window.location.replace(url+'/common/report/area-wise-order');
            }
        });

        $('[name=district]').on('change',function(){
            let div = $('[name=division]').val();
            let dis= $(this).val();
            var start = $('[name=start_date]').val().replace("/","-").replace("/","-");
            var end = $('[name=end_date]').val().replace("/","-").replace("/","-");

            window.location.replace(url+'/common/report/area-wise-order'+'?division='+div+'&district='+dis+'&start='+start+'&end='+end)
        });

        $('.orders').on('click',function(){
            $('#orderModal').modal('show');
            $('.order_result').html('Loading data....');
            var customer_ids = $(this).data('ids');
            if(customer_ids.length >0){
                $.get(url+'/common/report/area-wize-customer-orders/'+customer_ids, function(data){
                    $('.order_result').html(data);
                })
            }else $('.order_result').html('No data');
            
            
        });

        $('.customers').on('click',function(){
            $('#customerModal').modal('show');
            var customer_ids = $(this).data('ids');
            $('.customer_result').html('Loading...');
            if(customer_ids.length >0){
                $.get(url+'/common/report/area-wize-customers/'+customer_ids, function(data){
                    $('.customer_result').html(data);
                })
            }else $('.customer_result').html('No data');
            
        });

        $('.searchDateBtn').on('click', function(){
            var start = $('[name=start_date]').val().replace("/","-").replace("/","-");
            var end = $('[name=end_date]').val().replace("/","-").replace("/","-");
            let div = $('[name=division]').val();
            let dis= $('[name=district]').val();

            if(start =='' || end==''){
                alert('Please select date rage');
            }else{
                 window.location.replace(url+'/common/report/area-wise-order'+'?division='+div+'&district='+dis+'&start='+start+'&end='+end)
            }
        })
        
    })
</script>

@endpush