
Name: {{ $order->ship_first_name.' '.$order->ship_last_name }} <br>

Phone: {{ $order->ship_phone }} <br>
@if($order->ship_email !=null)
    Email: {{ $order->ship_email }} <br>
@endif

Area: {{ $order->division }} <i class="fa fa-arrow-right"></i> {{ $order->district }} <i class="fa fa-arrow-right"></i>
 {{ $order->city }} <br>

Address: {{ $order->address }}
