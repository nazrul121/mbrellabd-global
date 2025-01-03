<div class="form-group">
    <label for="recipient-name" class="col-form-label">Category Name</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Display Name - <small class="text-success">Title shows on category product</small></label>
    <input type="text" class="form-control" name="display_name">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Category Photo - <small>Optional</small> - [600x600px] - [format: <code>.png</code> ]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
            

        </div>
        <div class="col-md-4 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:100px">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5"></textarea>
</div>

<div class="form-group bg-light p-2">
    <p class="text-info">Country for--</p>
    <?php $__currentLoopData = get_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <label class="form-label">
            <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="<?php echo e($item->id); ?>"> <span></span>
            <span> <img class="flag" style="height:10px;" src="<?php echo e(url($item->flag)); ?>"> <?php echo e($item->short_name); ?></span>
        </label> &nbsp; &nbsp; 
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>


<div class="form-group">
    <label class="form-label">
        <input type="radio" class="status" name="status" value="1"> <span></span>
        <span>Published</span>
    </label>
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/common/category/main/form.blade.php ENDPATH**/ ?>