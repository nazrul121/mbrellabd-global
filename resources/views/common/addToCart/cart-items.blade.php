<table class="table table-hover m-0">
    <tr class="bg-light">
        <th>Product info</th>  <th>Qty</th>
    </tr>
    @foreach ($cartlist as $cart)
        <tr>
            <td>{{$cart->product->title}}
                @if($cart->product_combination_id !=null) -
                    @foreach (explode('~', $cart->product_combination->combination_string) as $item)
                        <b class="badge badge-info">{{$item}}</b>
                    @endforeach
                @endif
            </td>
            <td>{{$cart->qty}}</td>
        </tr>
    @endforeach

    @if($cart->user_id !=null)
        <?php $customer = \DB::table('customers')->where('user_id',$cart->user_id)->first();?>
        @if($customer !=null)
            <tr>
                <td colspan="3"><b>Customer:</b>  {{$customer->first_name}} {{$customer->last_name}} - {{$cart->user->phone}}
                @ {{date('d M, Y g:ia', strtotime($cart->created_at))}} <b>{{$cart->created_at->diffForHumans()}}</b> </td>
            </tr>
        @endif
        @else 
        <tr class="text-danger">
            <td colspan="3"> No customer info</td>
        </tr>    
    @endif
</table>