<div class="table-responsive">
    <table class="table tableDistirct">
        <thead>
            <tr>
                <th>#</th>
                <th class="text-left uppercase">District Name</th>
                <th class="text-left uppercase">Delivery charge</th>
                <th class="text-right uppercase">Modify</th>
            </tr>
        </thead>
    </table>
</div>
<!-- edit district  -->
<div class="modal" id="editDistrict"  data-animations="bounceInDown, bounceOutUp" data-static-backdrop>
    <div class="modal-dialog" role="document">
        <form class="modal-content" id="editDistrictForm" method="post" action="{{ route('update-district') }}"> @csrf
            <div class="modal-header">
                <h4 class="modal_title">Edit District <span class="disName text-info"></span></h4>
                <button type="button" class="closeIn"> <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="dis_result" style="width:100%"></div>
                <input type="hidden" name="id" >
                <input type="hidden" name="division_id" >
                @include('common.area.area.form.district')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary closeIn" >Close</button>
                <button type="submit" class="btn btn-primary ml-2">Update District</button>
            </div>
        </form>
    </div>
</div>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.tableDistirct').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="/storage/images/ajax-loader.gif">'},
            ajax: "{{route('division-to-districts',$division->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex',orderable:false,searchable:false},
                {data: 'name'},
                {data: 'delivery_cost'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.tableDistirct').on('click', '.editDistrict' ,function(e){
            let id = $(this).data('id');
            $('#editDistrict').modal('show');
            $('#editDistrictForm').css('box-shadow','1px 6px 6px #748892');
            $.get(url+'/common/area/single-district/'+id, function (data, textStatus, jqXHR) {
                $('[name=division_id]').val(data.division_id);
                $('[name=id]').val(data.id);
                $('[name=name]').val(data.name);
                $('[name=delivery_cost]').val(data.delivery_cost);
                $('[name=url]').val(data.url);
                console.log(data)
            });
        });

        $("#editDistrictForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.form_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false, dataType:"json",
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div style="width:100%" class="alert alert-warning alert-dismissible" role="alert"> <div class="alert-message"> <strong>Warning!</strong> ';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div style="width:100%" class="alert alert-success alert-dismissible" role="alert"> <div class="alert-message"> <strong>Success!</strong> ' + data.success +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button> </div>';
                        $('.tableDistirct').DataTable().ajax.reload();
                        setTimeout(function() { $('#addDistrict').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save District');
                    $("[type='submit']").prop('disabled',false);
                    $('.dis_result').html(html);
                }
            });
        });


        $('.tableDistirct').on('click', '.deleteDistrict' ,function(e){
            if(confirm('Are you sure to remove the record forever??')){
                let id = $(this).data('id');
                $.get(url+"/common/area/delete-district/"+id, function(data, status){
                    if(data.warning) alert(data.warning);
                    else $('.tableDistirct').DataTable().ajax.reload();
                });
            }
        });

        //close district edit modal
        $('.closeIn').on('click',function(){
            $('#editDistrict').modal('hide');
        });

    });
</script>

