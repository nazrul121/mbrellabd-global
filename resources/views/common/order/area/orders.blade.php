<table class="table table-hover">
    <tr><th>SL</th>
        <th>Invoice ID</th>
        <th>Product details</th>
        <th>Date</th>
    </tr>
    @foreach ($orders as $key=>$order)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$order->invoice_id}}</td>
            <td style="cursor:pointer; white-space:unset;width:68%;" data-id="{{$order->id}}">
                @if($order->order_items()->count()>0)
                <ul class="p-0 m-0" style="list-style:auto">
                    @foreach ($order->order_items()->get() as $item)
                       <li> {{$item->product->title}}  -  Qty: {{$item->qty}} , Price: {{$order->country->currencySymbol}} {{$item->sale_price}}</li>
                    @endforeach
                </ul>
                @endif
            </td>
            <td>{{date('d M, y', strtotime($order->created_at))}}</td>
        </tr>
    @endforeach
</table>

