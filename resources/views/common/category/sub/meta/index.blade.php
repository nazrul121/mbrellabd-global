
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>{{$inner_group->title}} <code>Meta description</code></h5>
                <div class="card-header-right">
                    @if(check_access('create-product-meta'))
                    <a href="#" class="btn btn-outline-primary addMetaModal"><span class="feather icon-plus"></span> Create Meta</a>@endif
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white metaTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th> <th>Meta Type</th><th>Meta Description</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.category.sub.meta.modal')
</div>


<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.metaTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.inner-group-meta',$inner_group->id)}}",

            columns: [
                {data: 'id'},
                {data: 'meta_type'}, {data: 'meta_content'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.metaTable').on('click','.edit',function(){
            $('#editMetaModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
   
            $.ajax({
                url: url+"/common/catalog/sub/meta/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('#editMetaForm').attr('action', url+'/common/catalog/sub/meta/update/'+id);
                    $('[name=meta_type] option[value="'+data.meta_type+'"]').prop('selected', true);
                    $('[name=meta_content]').val(data.meta_content);
                }
            });
        })

        $('.addMetaModal').on('click',function(){
            $('#addMetaModal').modal('show'); $('#addMetaForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
        })

        $("#addMetaForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.add_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.metaTable').DataTable().ajax.reload();
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });

        $("#editMetaForm").submit(function(event) {
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
                        $('.metaTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#editMetaModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.metaTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/catalog/sub/meta/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.metaTable').DataTable().ajax.reload();
                    }
                });
            }
        });



        $('.colseEditMeta').on('click', function(){
            $('#editMetaModal').modal('hide');
        })
        $('.colseAddMeta').on('click', function(){
            $('#addMetaModal').modal('hide');
        })

    });

</script>
