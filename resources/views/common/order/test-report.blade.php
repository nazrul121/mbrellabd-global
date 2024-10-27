
@extends('common.layouts')

@section('content')
<?php 
if(request()->start_date && request()->end_date){
    $url = '/common/report/order-export?start_date='.request()->start_date.'&end_date='.request()->end_date;
    $excel_url = '/common/report/order-excel?start_date='.request()->start_date.'&end_date='.request()->end_date;
    $pdf_url = '/common/report/order-pdf?start_date='.request()->start_date.'&end_date='.request()->end_date;
}else {
    $url = '/common/report/order-export';
    $excel_url = '/common/report/order-excel';
    $pdf_url = '/common/report/order-pdf';
}
?>
<input type="hidden" id="order_url" value="{{$url}}">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5><b>Order excel or PDF</b>  reporting &nbsp; &nbsp; 
                    <button class="btn btn-sm btn-primary refresh"><b class="feather icon-refresh-cw"></b></button>
                    <a href="{{$excel_url}}" class="btn btn-info excelBtn" target="_"><b class="fas fa-file-excel"></b> Excel</a>
                    <a href="{{$pdf_url}}" class="btn btn-secondary pdfBtn"target="_"><b class="fas fa-file-pdf"></b> PDF</a>
                </h5>
                    <div class="card-header-right">
                        <div class="input-daterange input-group" id="datepicker_range">
                            <input type="text" class="form-control text-left" placeholder="Start date" name="start_date" value="{{ str_replace('-','/',request()->start_date) }}">
                            <input type="text" class="form-control text-right" placeholder="End date" name="end_date" value="{{ str_replace('-','/',request()->end_date) }}">
                            <button type="button" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> Searach</button>
                        </div>
                    </div>

                </div>

                <div class="card-body">
                    <table class="table bg-white table-hover table-bordered orderTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Order No</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Billing info</th>
                                <th>Shipping info</th>
                                <th>Category</th>
                                <th>Design_code</th>
                                <th>Barcode</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Disc %</th>
                                <th>Disc Name</th>
                                <th>Disc Amt</th>
                                <th>Tax</th>
                                <th>Tax line</th>
                                <th>Del charge</th>
                                <th>Net amount</th>
                                <th>Payment Method</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Courier Name</th>
                                <th>Delivery Date</th>
                                <th>Cust. ID</th>
                            </tr>
                            @foreach ($orders as $row)
                                <tr>
                                    <td>{{date('m/d/Y h:i', strtotime($row->created_at))}}</td>
                                    <td>
                                        @if($row->order_id !=null)
                                            {{$row->order->invoice_id}}
                                        @else {{ $row->id.' does not have order' }} @endif
                                    </td>
                                    <td>
                                        @if($row->order_id !=null)
                                           {{$row->order->customer->first_name.' '.$row->order->customer->last_name}}
                                        @else {{$row->id.' does not have order'}}  @endif
                                    </td>
                                    <td>
                                        @if($row->order_id !=null)
                                           {{$row->order->customer->user->phone}}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                        if($row->order_id !=null){
                                            $data = 'Name: '.$row->order->customer->first_name.' '.$row->order->customer->last_name.', Phone:'.$row->order->customer->user->phone.', ';
                                            if($row->order->customer->district){ $data .=  ', Distict: '.$row->order->customer->district->name;}
                                            if($row->order->customer->city){ $data .= ', City: '.$row->order->customer->city->name; }
                                
                                            $data .= ', Address:'.$row->order->customer->address;
                                        }
                                        @endphp
                                        {{$data}}
                                    </td>
                                    <td>
                                        @php
                                            if($row->order_id !=null){
                                                $data = 'Name: '.$row->order->shipping_address->fname.' '.$row->order->shipping_address->lname;
                                                $data .= ', Phone: '.$row->order->shipping_address->phone.', Email: '.$row->order->shipping_address->email;
                                    
                                                if($row->order->shipping_address->district){ $data .=  ', Distict: '.$row->order->shipping_address->district->name;}
                                                if($row->order->shipping_address->city){ $data .= ', City: '.$row->order->shipping_address->city->name; }
                                                $data .= ', Address:'.$row->order->shipping_address->address;
                                            }
                                        @endphp     
                                        {{$data}}
                                    </td>
                                    <td>
                                        @if($row->order_id !=null)
                                            {{\DB::table('products')->where('id',$row->product_id)->pluck('title')->first()}}
                                        @endif
                                    </td>
                                    <td>{{\DB::table('products')->where('id',$row->product_id)->pluck('design_code')->first()}}</td>
                                    <td>
                                        @if($row->order_id !=null)
                                            {{\DB::table('product_combinations')->where('id',$row->product_combination_id)->pluck('barcode')->first()}}
                                        @else 
                                            No order_id
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->order_id !=null)
                                           {{$row->qty}}
                                        @endif
                                    </td>
                                    <td>
                                        @if($row->order_id !=null)
                                            {{$row->sale_price}}
                                        @endif
                                    </td>
                                    <td>
                                        @php
                                            if($row->promotion_id==null){
                                                if($row->outlet_percent!=null) $dis_data = $row->outlet_percent;
                                                else $dis_data =  0;
                                            }else{
                                                $pP = \DB::table('product_promotion')->where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id])->select('discount_in','discount_value')->first();
                                                if($pP==null){
                                                    $data .='order_item_id: '.$row->id.', promo id: '.$row->promotion_id;
                                          
                                                }else{
                                                    if($pP->discount_in=='percent'){
                                                        $dis_data = $pP->discount_value;
                                                    }else $dis_data = $pP->discount_value;
                                                }
                                                
                                            }
                                        @endphp 
                                        {{$dis_data}}
                                    </td>
                                </tr>
                            @endforeach

                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

@endsection
