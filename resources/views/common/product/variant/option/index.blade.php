
<div class="container">
    <div class="table-responsive">
        <table class="table table-hover bg-white optionTable" style="width:100%">
            <thead>
                <tr>
                    <th>#</th> <th>Title</th>
                    <th>Code</th> <th>Products</th> <th>Status</th> <th>Actions</th>
                </tr>
            </thead>
        </table>
    </div>
</div>



<div class="modal fade" id="editExtModal" tabindex="-1" role="dialog" aria-labelledby="editExtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editExtForm" class="modal-content" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editExtModalLabel">Edit Variation</h5>
                <button type="button" class="close-modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="editExt_result"></div>
                @include('common.product.variant.option.form')
                <input type="hidden" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

<script>

    $(function(){
        $(function () { table.ajax.reload(); });

        let table = $('.optionTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.variant-option',$variation->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},  {data: 'title'}, {data: 'code'},
                {data: 'products', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.optionTable').on('click','.edit',function(){
            $('#editExtModal').modal('show'); $('.editExt_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/catalog/category/variant-option/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=origin]').val(data.origin);
                    $('[name=code]').val(data.code);
                    $('[name=variation_id]').val(data.id);
                    $('#editExtForm').attr('action', url+'/common/catalog/category/variant-option/update/'+data.id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);
                }
            });
        })



        $("#editExtForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html('Loading...');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                        $('.optionTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#editExtModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.editExt_result').html(html);
                }
            });
        });

        $('.optionTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/catalog/category/variant-option/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.optionTable').DataTable().ajax.reload();
                    }
                });
            }
        });


        $('.close-modal').on('click',function(){
            $('#editExtModal').modal('hide');
        })

    })
</script>
