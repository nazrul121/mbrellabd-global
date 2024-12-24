<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="<?php echo e(route('common.sub-category.create')); ?>" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create Sub-category</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                <?php echo $__env->make('common.category.sub.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm" class="modal-content" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit category</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                <?php echo $__env->make('common.category.sub.form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <input type="hidden" name="id">
                <input type="hidden" class="oldPhoto" name="oldPhoto">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="metaModal" tabindex="-1" role="dialog" aria-labelledby="metaLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sub-category meta information <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body metaResult"> </div>
        </div>
    </div>
</div>


<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/common/category/sub/modal.blade.php ENDPATH**/ ?>