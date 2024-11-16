
    <input type="hidden" name="id">
    <div class="input-field">
        <label class="active">Vairation</label>
        <select name="variation_id" class="form-control">
            <option value="">Choose one</option>
            <?php $__currentLoopData = $variation_options; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($vo->variation_id.'|'.$vo->id); ?>"><?php echo e($vo->title); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-8">
                <label class="active">Upload Photo [single]</label>
                <input type="file" class="form-control" name="photo">
            </div>
            <div class="col-md-4 text-center ">
                <img id="output" class="setPhoto mt-4" src="<?php echo e(url('storage/images/thumbs_photo.png')); ?>" style="height:60px;max-width:100%">
            </div>
        </div>
    </div>

<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/variation-photo/form.blade.php ENDPATH**/ ?>