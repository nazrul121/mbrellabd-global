@extends('common.layouts')

<?php 
$statuses = [];
if($product){
    foreach($product->order_items()->get() as $key=>$oi){
        $statuses[] = $oi->order->order_status_id;
    }
    $statuses = array_unique($statuses);
    sort($statuses);
    $orderStatus = DB::table('order_statuses')->whereIn('id',$statuses)->select('id','title')->get();
}

$statusWiseCount = 0;

?>

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Single Proudct orders &nbsp;  </h5>
                    <div class="card-header-right">
                        <form class="input-daterange input-group">@csrf
                            <input type="text" class="form-control text-left" placeholder="Product info" name="product" value="{{request()->get('product')}}">
                            <button type="submit" class="btn btn-dark"><i class="fa fa-search"></i> Search</button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        @if($product)
                            <div class="text-left bg-light text-dark p-4">
                                <img src="{{url('storage/'.$product->thumbs)}}" style="float:right;height:100px;border:2px solid silver;">
                                <b>Title</b>: {{$product->title}} <br>
                                <b>Design Code</b>: {{$product->design_code}} <br>
                                <b>Net Price</b>: {{$product->net_price}} <br>
                                <b>Sale Price</b>: {{$product->sale_price}} <br>
                                <div class="card code-table" style="width: 100%;">
                                    <div class="card-header">
                                        <h5> Total Sale: <b class="badge badge-light text-danger p-3">{{$product->order_items()->count()}}</b>  </h5>
                                        <select name="status" class="float-right p-2">
                                            <option value="">Choose Status</option>
                                            @foreach($orderStatus as $status)
                                                <option @if(request()->get('status')==$status->id)selected @endif value="{{$status->id}}">{{$status->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="card-block pb-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr class="text-center">
                                                        <th>#</th>
                                                        <th>Date</th>
                                                        <th>Transction ID</th>
                                                        <th>Order Qty</th>
                                                        
                                                        <th>Budget</th>
                                                        <th class="text-right">Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($product->order_items()->get() as $key=>$oi)
                                                    @if(request()->get('status'))
                                                     @if(request()->get('status')==$oi->order->order_status_id)
                                                        <?php $statusWiseCount++;?>
                                                        <tr class="text-center">
                                                            <td>{{$key+1}}</td>
                                                            <td><h6 class="mb-1">{{date('d/m/Y',strtotime($oi->order->order_date))}}</h6> </td>
                                                            <td><h6 class="mb-1">#{{$oi->order->transaction_id}}</h6> </td>
                                                            <td><h6 class="">{{$oi->qty}}</h6> </td>
                                                            <td>
                                                                <h6 class="mb-1">{{$oi->discount_price}}</h6>
                                                            </td>
                                                            <td class="text-right">
                                                                <h6 class="m-b-0 @if (strpos($oi->order->order_status->title, 'Delivered') !== false)text-success @else text-danger @endif">{{$oi->order->order_status->title}}</h6>
                                                            </td>
                                                        </tr>
                                                     @endif
                                                    @else
                                                        <?php $statusWiseCount++;?>
                                                        <tr class="text-center">
                                                            <td>{{$key+1}}</td>
                                                            <td><h6 class="mb-1">{{date('d/m/Y',strtotime($oi->order->order_date))}}</h6> </td>
                                                            <td><h6 class="mb-1">#{{$oi->order->transaction_id}}</h6> </td>
                                                            <td><h6 class="">{{$oi->qty}}</h6> </td>
                                                            <td>
                                                                <h6 class="mb-1">{{$oi->discount_price}}</h6>
                                                            </td>
                                                            <td class="text-right">
                                                                <h6 class="m-b-0 @if (strpos($oi->order->order_status->title, 'Delivered') !== false)text-success @else text-danger @endif">{{$oi->order->order_status->title}}</h6>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                                </tbody>
                                               
                                                <tr>
                                                    <td colspan="6">Total count: <b>{{$statusWiseCount}}</b></td>
                                                </tr>
                                               
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else 
                            <p class="text-center bg-light p-4 text-danger">No product found with given keyword</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(function(){
            $('[name=status]').on('change', function() {
                var selectedOption = $(this).val();

                var fullUrl = window.location.href;
                // Parse the URL to manipulate it
                var url = new URL(fullUrl);

                // Specify the parameter name you want to remove (e.g., "paramToRemove")
                var paramToRemove = "status";

                // Check if the parameter exists in the URL
                if (url.searchParams.has(paramToRemove)) {
                    // Remove the parameter and its value
                    url.searchParams.delete(paramToRemove);

                    // Get the modified URL without the parameter
                    var newUrl = url.toString();

                    // Redirect to the modified URL
                    window.location.href = newUrl;
                    // Get the selected option's value
                
                    // Redirect to the selected URL
                    window.location.href = url+'&status='+selectedOption;
                }else{
                    window.location.href = fullUrl+'&status='+selectedOption;
                }
            });

            
        })
    </script>
@endpush