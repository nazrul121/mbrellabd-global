
<p class="text-info bg-light p-3">Bunle ID: {{$courier_order_bundle->bundle_id}}, Company: {{$courier_order_bundle->courier_company->name}}</p>

<?php   
    $inTotal = array();
    $zones = DB::table('courier_zones')->where('courier_company_id',$courier_order_bundle->courier_company_id)->get();
    $courier_company_order = DB::table('courier_company_orders')->where('courier_order_bundle_id',$courier_order_bundle->id)->first();

?>
    

<div class="table-responsive">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Invoice ID</th>
                <th>Order items</th>
                @foreach($zones as $zone)
                    <th>{{$zone->name}}</th>
                @endforeach
                <th class="text-right">Del. status</th>
                <th class="text-right">Price</th>
            </tr>
        </thead>

    
        @foreach($orders as $key=>$order)
            <?php 
                $total = array();
            ?>
            <tr>
                <td>{{$key+1}}</td>
                <td>{{$order->invoice_id}}</td>
                <td>
                    @foreach($order->order_items()->get() as $item)
                        {{$item->product->title}} = {{$item->qty}} x {{$item->discount_price}} <br>
                        <?php $total[] = $item->qty * $item->discount_price; ?>
                    @endforeach
                </td>
        
                @foreach($zones as $zone)
                    <th>
                    <?php $cco_zone = \DB::table('courier_company_orders')->where('order_id',$order->id)->first();?>    
                    @if($cco_zone->courier_zone_id==$zone->id)
                        {{$cco_zone->delivery_cost}}
                    @else 0 @endif 
                    </th>
                @endforeach
                <td>
                    @if (strpos(strtolower($order->order_status->title), 'return') !== false) <span class="text-danger">{{$order->order_status->title}}</span>
                    @elseif(strpos(strtolower($order->order_status->title), 'deliver') !== false) <span class="text-success">{{$order->order_status->title}}</span>
                    @elseif(strpos(strtolower($order->order_status->title), 'shipped') !== false) <span class="text-warning">{{$order->order_status->title}}</span>
                    @elseif(strpos(strtolower($order->order_status->title), 'cancelled') !== false) <span class="text-danger">{{$order->order_status->title}}</span>
                    
                    @else {{$order->order_status->title}} @endif
                </td>
                <td class="text-right">{{array_sum($total)}}</td>
                <?php $inTotal[] = array_sum($total);?>
                
            </tr>
        @endforeach
        <tfoot>
            <tr class="text-right" >
                <th colspan="{{$zones->count() + 4}}">Total: &nbsp; </th>
                <th colspan="1">{{array_sum($inTotal)}}</th>
            </tr>
        </tfoot>
    </table>
</div>