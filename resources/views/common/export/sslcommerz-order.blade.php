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

                $payerInfo = json_decode($item->payer_info, true); 
                if ($payerInfo && isset($payerInfo['card_type'])) {
                    $bankType =  $payerInfo['card_type'];
                } else {
                    $bankType = '';
                }
                // dd($payerInfo['card_type']);

            @endphp
            @if ($item->order !=null && $item->order->order_status_id !==10)
                <tr>
                    <th scope="row"> {{ $key+1 }}</th>
                    <td>{{ $item->order->transaction_id }} </td>
                    <td><b>{{ $item->order->order_items()->count() }}</b></td>
                    <td>{{ $item->payment_type->title }} - {{ $bankType }} </td>
                    <td class="text-center">{{  $item->order->total_cost }}</td>                                      
                    <td >{{ $item->order->shipping_cost }}</td>
                    <td class="text-center"> @if ($item->order->invoice_discount!=0.00) Free delivery @endif</td>
            
                    <td class="text-right">{{ $item->amount }}</td>
                    <td class="text-center"> {{ date('d M, Y: h:ia',strtotime($item->created_at)) }} </td>
                    <td class="text-right"> {{ $item->status }} </td>
                </tr>
            @endif
        @endforeach
    </tbody>

</table>