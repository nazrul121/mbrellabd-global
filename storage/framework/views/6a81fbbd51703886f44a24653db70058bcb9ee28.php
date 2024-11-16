<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="<?php echo e(route('common.product-meta.create')); ?>" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add new meta info</h5>
                <button type="button" class="colseAddMeta" ><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                <?php echo $__env->make('common.product.meta.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary colseAddMeta">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editMetaForm" class="modal-content" action="#" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Meta information</h5>
                <button type="button" class="close-modal colseEdotMeta" ><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                <?php echo $__env->make('common.product.meta.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <input type="hidden" name="product_id" value="<?php echo e($product->id); ?>">
                <input type="hidden" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary colseEdotMeta">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>




<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/meta/modal.blade.php ENDPATH**/ ?>