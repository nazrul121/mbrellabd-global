
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Product <code>Meta description</code></h5>
                <div class="card-header-right">
                    <?php if(check_access('create-product-meta')): ?>
                    <a href="#" class="btn btn-outline-primary addModal"><span class="feather icon-plus"></span> Create Meta</a><?php endif; ?>
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
    <?php echo $__env->make('common.product.meta.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>


<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.metaTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e(route('common.product-meta',$product->id)); ?>",

            columns: [
                {data: 'id'},
                {data: 'meta_type'}, {data: 'meta_content'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.metaTable').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/catalog/product/meta/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('#editMetaForm').attr('action', url+'/common/catalog/product/meta/update/'+id);
                    $('[name=meta_type] option[value="'+data.meta_type+'"]').prop('selected', true);
                    $('[name=meta_content]').val(data.meta_content);
                }
            });
        })

        $('.addModal').on('click',function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
        })

        $("#addForm").submit(function(event) {
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
                        setTimeout(function() { $('#addModal').modal('hide');}, 1000);
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
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
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
                    url: url+"/common/catalog/product/meta/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.metaTable').DataTable().ajax.reload();
                    }
                });
            }
        });



        $('.colseEditMeta').on('click', function(){
            $('#editModal').modal('hide');
        })
        $('.colseAddMeta').on('click', function(){
            $('#addModal').modal('hide');
        })

    });

</script>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/meta/index.blade.php ENDPATH**/ ?>