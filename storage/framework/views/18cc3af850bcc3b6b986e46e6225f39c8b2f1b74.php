
<div class="modal fade" id="VarientModal" tabindex="-1" role="dialog" aria-labelledby="varientLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document" style="max-width: 70%">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Varients of <b id="varientLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body variantArea"> </div>

        </div>
    </div>
</div>

<div class="modal fade" id="colorModal" tabindex="-1" role="dialog" aria-labelledby="colorLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Colors and photos <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body colorResult"> </div>

        </div>
    </div>
</div>



<div class="modal fade" id="metaModal" tabindex="-1" role="dialog" aria-labelledby="metaLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Product meta information <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body metaResult"> </div>

        </div>
    </div>
</div>


<div class="modal fade" id="quickEditModal" tabindex="-1" role="dialog" aria-labelledby="metaLable" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Quick edit <b id="colorLable"></b></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="quick_result"></div>

                <form id="quickEditForm" action="" method="POST" class="needs-validation" ><?php echo csrf_field(); ?>
                    <input type="hidden" name="id" id="id">
                   
                    <?php $__currentLoopData = get_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="form-group">
                            <div class="checkbox checkbox-info checkbox-fill d-inline">
                                <input type="checkbox" name="langs[]" type="checkbox" value="<?php echo e($item->id); ?>" id="c<?php echo e($key); ?>" checked>
                                <label for="c<?php echo e($key); ?>" class="cr"> <img src="<?php echo e(url($item->flag)); ?>" style="height:12px; margin:5px;"> 
                                    <?php echo e($item->name); ?>

                                </label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <hr>

                    <div class="form-group">
                        <div class="checkbox checkbox-info checkbox-fill d-inline">
                            <input type="checkbox" name="newArrival" id="newArrival">
                            <label for="newArrival" class="cr">This is  <b>newArrival</b> item</label>
                        </div>
                    </div>
                   
                    <div class="mb-3 col-md-12">
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary submitQuickEdit"><i class="fas fa-edit"></i> Submit edit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/modal.blade.php ENDPATH**/ ?>