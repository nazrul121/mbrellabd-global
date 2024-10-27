
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Customers orders</h5>
                <div class="card-header-right">
                    <form class="form-inline">@csrf
                        <div class="form-group mb-2">
                            <label for="staticEmail2" class="sr-only">Customer Name / Phone</label>
                            <input type="text" readonly="" class="form-control-plaintext" id="staticEmail2" value="Customer Name / Phone">
                        </div>
                         <div class="form-group mx-sm-3 mb-2">
                        <label class="sr-only">info</label>
                        <input type="text" class="form-control" name="customer" placeholder="phone / Name" value="{{request()->get('customer')}}">
                        </div>
                        <button type="submit" class="btn btn-primary mb-2">Search</button>
                    </form>
                </div>
            </div>

            <div class="card-body">
                @if($customer !=null)
                    <div class="row">
                        <div class="col-md-6 col-xl-4">
                            <div class="card project-task">
                                <div class="card-block">
                                    <div class="row align-items-center justify-content-center">
                                        <img src="{{url('storage/'.$customer->photo)}}" alt="{{$customer->first_name}}">
                                    </div>
                                    <div class="card-header">
                                        <h5>{{$customer->first_name.' '.$customer->last_name}} ( {{$customer->phone}} )</h5>
                                        <div class="card-header-right">
                                            <div class="btn-group card-option show"> Balance: {{$customer->balance}} </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h6 class="mt-3 mb-0 text-center text-muted">The customer has <b>{{$customer->orders()->count()}}</b> <small>Orders</small></h6>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-xl-8">
                            <div class="card project-task">
                                <div class="card-block">
                                    @if($customer->orders()->count() <1)
                                        <p class="text-center text-danger bg-light p-5">The customer has <b>No</b> Orders yet.</p>
                                    @else 
                                        <div class="responsive-table" style="height:80vh;overflow-y:overlay;">
                                            <table class="table table-hover table-bordered">
                                                <thead>
                                                    <tr class="bg-light">
                                                        <td>#</td>
                                                        <td>Date</td>
                                                        <td>Invoice No</td>
                                                        <td>item Qty</td>
                                                        <td>Price</td>
                                                        <td>Status</td>
                                                        <td>More</td>
                                                    </tr>
                                                    @foreach ($customer->orders()->get() as $key=>$order)
                                                        <tr>
                                                            <td>{{$key+1}}</td>
                                                            <td>{{date('d F, Y',strtotime($order->order_date))}}</td>
                                                            <td>{{$order->transaction_id}}</td>
                                                            <td>{{$order->total_items}}</td>
                                                            <td>{{$order->total_cost}}</td>
                                                            <td>{{$order->order_status->title}}</td>
                                                            <td>
                                                                <a href="{{route('common.order.invoice',$order->id)}}" class="btn btn-primary btn-sm"><i class="fa fa-print"></i> Print</a>
                                                                <a href="{{route('common.edit-order',$order->id)}}" class="btn btn-warning btn-sm"><i class="fa fa-edit"></i> Edit</a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </thead>
                                            </table>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                
                @if(request()->get('customer') && $customer==null)
                    <p class="text-center text-danger bg-light p-5">No Data found with given <b>phone</b> No.</p>
                @endif     
                
            </div>
        </div>
    </div>

</div>
@endsection
