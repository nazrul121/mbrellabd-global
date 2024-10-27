<table >
    <thead>
        <tr>
            <th>Date and Time</th>
            <th>Trans. ID</th>
            <th>Phone</th>
            <th>Qty</th>
            <th>Total Cost</th>
            <th>Delivery status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $key=>$row)
            <?php 
                $qtyPrice = DB::table('order_items')->where('order_id',$row->id)
                ->selectRaw('SUM(qty * discount_price) as total_cost')
                ->value('total_cost');
            ?>
            <tr>
                <td>{{date('m/d/Y h:i', strtotime($row->created_at))}}</td>
                <td>{{$row->transaction_id}}</td>
                <td>{{$row->first_name.' '.$row->last_name}}/{{$row->phone}}</td>
                <td>{{$row->order_items()->count()}}</td>
                <td>
                 {{$qtyPrice}}
                </td>
                <td>{{$row->order_status->title}}</td>
            </tr>
        @endforeach
    </tbody>
</table>


<style>
    table, td, th {  
        border: 1px solid #ddd;
        text-align: left;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th, td {
        padding: 15px;
    }
</style>

