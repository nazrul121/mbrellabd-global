<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Product updation form </h5>
                <div class="card-header-right">
                    <a href="<?php echo e(route('common.product')); ?>" class="btn btn-outline-primary addModal"><i class="feather icon-list"></i> View products</a>
                </div>
            </div>

            <?php if( Session::has('success') || Session::has('alert') ): ?>
                <div class="card-body">
                    <div class="alert <?php if(Session::has('success')): ?> alert-success <?php else: ?> alert-danger <?php endif; ?> alert-dismissible fade show" role="alert">
                        <h5> <strong> <?php if(Session::has('success')): ?>Success :  <?php echo e(session()->get('success')); ?>

                        &nbsp; &nbsp; &nbsp; You may add variants now
                            <a href="<?php echo e(route('common.product')); ?>?product=<?php echo e(Session::get('id')); ?>&title=<?php echo e(Session::get('title')); ?>">Click here</a>
                        <?php else: ?> Warning: <?php echo e(Session::get('alert')); ?>  <?php endif; ?>

                        </strong> </h5>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div> 
                </div>
            <?php endif; ?>
            
        </div>
    </div>
    <form id="addForm" class="col-12" style="position:relative;top:-25px" action="<?php echo e(route('common.product.update',$product->id)); ?>" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
        <?php echo $__env->make('common.product.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </form>
</div>

<div class="modal fade" id="photo_modal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Photos</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3" ><div class="row show_photos"></div></div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function(){
            $('.photoModal').on('click',function(){
                $('#photo_modal').modal('show'); $(".show_photos").html('');
                $.get("<?php echo e(route('common.product-photos',$product->id)); ?>", function( data ) {
                    $.each(data, function(index, value){
                        $(".show_photos").append('<div class="col-md-6 text-center row_'+value.id+'"><img style="max-width:100%;margin-top:1em;" src="'+value.photo+'"><a href="javaScript:;" id="'+value.id+'" class="rmvSinglePhoto"> Remove photo</a></div>');
                    });
                });
            });

            $('.show_photos').on('click','.rmvSinglePhoto',function(){
                let id =$(this).attr('id');
                $.get( url+"/common/remove-product-photo/"+id, function() {
                    $('.row_'+id).remove();
                });
            })
        })
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/common/product/edit.blade.php ENDPATH**/ ?>