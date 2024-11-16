
@extends('common.layouts')

@section('title','SSLCOMMERZ orders')

@php
    $color = '';
    $succesItems = 0;
    $succesAmount = $succesShippingCost = [];
@endphp

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header row">
                    <div class="col-md-6">
                        <h5>Online <b>Payments</b></h5>
                        Total of <b>{{ $orderPayments->count() }}</b> records
                    </div>
                    <div class="col-md-6 text-md-right">
                        <select name="status" style="padding:9px;" class="border border-info">
                            <option value="">All status</option>
                            @foreach ($statuses as $item)
                                <option value="{{ $item->status }}"@if (request()->get('status')==$item->status)selected @endif >{{ $item->status }}</option>
                            @endforeach
                        </select>

                        <a href="{{ route('common.sslcommerz-excel') }}?status={{ request()->get('status') }}" target="_blank" class="btn btn-info excelBtn"><b class="fas fa-file-excel"></b> Excel</a>
                    </div>
                   
                   
                </div>
        
                <div class="card-body">
                    <div class="row">
                        <div class="card-body">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Trans. ID</th>
                                        <th scope="col">Total items</th>
                                        <th scope="col">Payment Type</th>
                                        <th scope="col" class="text-center">Amount</th>
                                        <th scope="col" class="text-center">Del. cost</th>
                                        <th class="text-md-center">Free del.</th>
                                        <th class="text-md-right">Total</th>
                                        <th scope="col" class="text-center">Date</th>
                                        <th scope="col" class="text-right">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orderPayments as $key=>$item)
                                        @php 
                                            if (strtolower($item->status)=='cancelled' || strtolower($item->status)=='failed' || strtolower($item->status)=='rejected'){
                                                $color = 'text-danger';
                                            }
                                            
                                            if (strtolower($item->status)=='valid' || strtolower($item->status)=='success' || strtolower($item->status)=='accepted'){
                                                $color = 'text-success';
                                                
                                                if($item->order !=null){
                                                    $succesAmount[] = $item->amount;
                                                    $succesShippingCost[] = $item->order->shipping_cost;
                                                    $succesItems += 1;
                                                }
                                                
                                            }
                                            $payerInfo = json_decode($item->payer_info, true); 
                                            if ($payerInfo && isset($payerInfo['card_type'])) {
                                                $bankType =  $payerInfo['card_type'];
                                            } else {
                                                $bankType = '';
                                            }
                                            // dd($payerInfo['card_type']);

                                            // if($item->order->order_status_id) dd($item->order);

                                        @endphp


                                        @if ($item->order !=null && $item->order->order_status_id !==10)
                                            <tr>
                                                <th scope="row"> {{ $key+1 }}</th>
                                                <td>{{ $item->order->transaction_id }} </td>
                                                <td><b>{{ $item->order->order_items()->count() }}</b></td>
                                                <td>{{ $item->payment_type->title }} - {{ $bankType }} </td>
                                                <td class="text-center">{{ $item->order->total_cost }}</td>                                      
                                                <td >{{ $item->order->shipping_cost }}</td>
                                                <td class="text-center"> @if ($item->order->invoice_discount!=0.00) Free delivery @endif</td>
                                        
                                                <td class="text-right">{{ $item->amount }}</td>
                                                <td class="text-center"> {{ date('d M, Y: h:ia',strtotime($item->created_at)) }} </td>
                                                <td class="text-right {{ $color }}"> {{ $item->status }} </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="text-md-right">
                                        <td colspan="4">Total successful order Items: <b>{{ $succesItems }}</b></td>
                                        <td colspan="4">Total Success transaction Amount: <b>{{ array_sum($succesAmount) + array_sum($succesShippingCost) }}</b></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('[name=status]').on('change', function(){
                var status = $(this).val();
                var url = new URL(window.location.href);
                url.searchParams.set('status',status);
                window.location.href = url.href;
            })
        });
    </script>
@endpush
