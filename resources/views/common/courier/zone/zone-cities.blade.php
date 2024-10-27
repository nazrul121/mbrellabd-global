
<div class="table-responsive">
    <table class="zoneCity table table-hover bg-white" style="width:100%">
        <thead>
            <tr> <th>#</th>
                <th class="text-left uppercase">City Name</th>
                <th class="text-right uppercase">Modify</th>
            </tr>
        </thead>
    </table>
</div>




<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
        $(function () { table.ajax.reload(); });
        let table = $('.zoneCity').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            "bFilter": false, "bInfo": false,
            ajax: url+"/common/area/zone/zone-cities/{{ $zone->id }}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'city', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.zoneCity').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).data('id')
                $.ajax({
                    url:url+"/common/area/zone/zone-city/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.zoneCity').DataTable().ajax.reload();
                    }
                });
            }
        });
    });

</script>


