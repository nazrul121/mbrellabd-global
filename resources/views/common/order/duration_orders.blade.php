<table class="table">
    <tr>
        <th>#</th>
        <th>Invoice ID</th>
        <th>Qty</th>
    </tr>
    @foreach ($orders as $key=>$item)
    <tr>
        <td>{{$key + 1}}</td>
        <td>{{$item->invoice_id}}</td>
        <td>{{$item->total_items}}</td>
    </tr>
    @endforeach
</table>