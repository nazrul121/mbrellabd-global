<table class="table table-hover table-borderer">
    <tr>
        <th>Name</th>
        <th>Phone No</th>
        <th>Bundle Collected</th>
        <th>Status</th>
    </tr>
    @foreach ($mans as $man)
    <tr>
        <td>{{ $man->name }}</td>
        <td>{{ $man->phone }}</td>
        <td><b>{{ $man->courier_oder_bundles()->count() }}</b> bundles</td>
        <td>
            @if($man->status=='1') <span class="badge badge-success">Active</span>
            @else <span class="badge badge-danger">Inactive</span> @endif
        </td>
    </tr>
    @endforeach

    @if($mans->count() <1)
        <tr class="text-danger text-center"> <td colspan="4">No Representatives found agains this company</td> </tr>
    @endif
</table>
