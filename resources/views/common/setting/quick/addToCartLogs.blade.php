<div class="table-responsive">
    <table class="table table-hover AddToCartable" style="width:100%">
        <thead>
            <tr>
                <td>#</td>
                <td>Status</td>
                <td>Date</td>
                <td>Updated By</td>
            </tr>
        </thead>
    </table>
</div>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.AddToCartable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: 'Loading...'},
            ajax: "{{route('common.addToCart-logs')}}",

            columns: [
                {data: 'id'},
                {data: 'status'},
                {data: 'created_at'},
                {data: 'user', orderable: false, searchable: false, class:'text-right'}
            ]
        });


    });
</script>