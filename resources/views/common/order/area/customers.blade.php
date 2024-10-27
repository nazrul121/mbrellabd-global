<table class="table table-hover">
    <tr>
        <th>SL</th>
        <th>Name</th>
        <th>phone</th>
        <th>Orders</th>
    </tr>
    @foreach ($customers as $key=>$item)
        <tr>
            <td>{{$key+1}}</td>
            <td>{{$item->first_name}} {{$item->last_name}}</td>
            <td>{{$item->user->phone}}</td>
            <td>{{$item->orders()->count()}}</td>
        </tr>
    @endforeach
</table>