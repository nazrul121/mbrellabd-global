
<div class="row">
    <div class="col-12">
        <div class="card">
            <p class="alert alert-info"> <img src="<?php echo e($product->thumbs); ?>" height="30"> <?php echo e($product->title.' ('.$product->id.')'); ?>

                <button class="btn btn-info float-right addVariatinPhoto">add photo</button>
            </p>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table productColor table-hover bg-white" style="width:100%">
                        <thead>
                            <tr> <th>#</th> <th>Variation</th> <th>Photos</th> <th class="text-right">Action</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php echo $__env->make('common.product.variation-photo.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        let table = $('.productColor').DataTable({
            processing: true,serverSide: true, "bFilter": false, "lengthChange": false,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e(route('common.product.variation.photo',$product->id)); ?>",
            columns: [
                {data: 'id'},
                {data: 'variation', orderable: false, searchable: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ],
        });

        $('.productColor').on('click','.edit', function(){
            let id = $(this).data('id');
            let variation_id = $(this).data('variation_id');
            let option_id = $(this).data('option_id');
            let old_photo = $(this).data('oldphoto');
            $('.setPhoto').attr('src',url+'/storage/'+old_photo)
            $('[name=variation_id]').val(variation_id);
            $('[name=option_id]').val(option_id);
            $('[name=old_photo]').val(old_photo);
            $('[name=id]').val(id);

            $('[name=variation_id] option[value="'+variation_id+'|'+option_id+'"]').prop('selected', true);
            $('#addModal').modal('hide');
            $('#editModal').modal('show'); $('#editForm').trigger("reset");
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.addResult').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';

            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.error) {
                        html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong> ' + data.error +'</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.productColor').DataTable().ajax.reload();
                    }
                    $("[type='submit']").text('Upload photo');
                    $("[type='submit']").prop('disabled',false);
                    $('.addResult').html(html);
                }
            });
        });

        $("#editVPhotoForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.editResult').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';

            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.error) {
                        html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong> ' + data.error +'</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.productColor').DataTable().ajax.reload();
                        $('#editModal').modal('hide');
                    }
                    $("[type='submit']").text('Update photo');
                    $("[type='submit']").prop('disabled',false);
                    $('.editResult').html(html);
                }
            });
        });

        $('.productColor').on('click','.delete', function(){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                var id = $(this).data('id');
                // alert(id); return false;
                $.ajax({
                    url: url+"/common/catalog/product/delete-product-variation-photo/"+id+"",   dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error); if(data.success) $('.productColor').DataTable().ajax.reload();
                    }
                });
            }
        })


        $('.close-photo-modal').on('click', function(){
            $('#addModal').modal('hide');
            $('#editModal').modal('hide');
        });

        $('.addVariatinPhoto').on('click',function(){
            $('#addModal').modal('show');
            $('#editModal').modal('hide');
        })

    });

</script>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/variation-photo/index.blade.php ENDPATH**/ ?>