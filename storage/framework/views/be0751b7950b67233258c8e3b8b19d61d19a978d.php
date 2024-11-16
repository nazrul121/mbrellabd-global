<div class="form-group">
    <label for="recipient-name" class="col-form-label">Season title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <div class="row">
        <label for="recipient-name" class="col-form-label col-md-3 text-md-right">Public Link</label>
        <div class="col-md-9">
            <input type="text" class="form-control" name="slug">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Season Photo - <small>Optional</small> - [1800x800px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img id="output" class="setPhoto" src="<?php echo e(url('storage/images/thumbs_photo.png')); ?>" style="height:100px;max-width:100%">
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
            <span> <img class="flag" style="height:13px;" src="<?php echo e(url($item->flag)); ?>"> <?php echo e($item->short_name); ?></span>
        </label> &nbsp; &nbsp; 
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="bg-light p-3">
    <div class="row">
        <div class="col-md-12 linkField">
            <div class="form-group">
                <label >Meta title</label>
                <input class="form-control" placeholder="Service Meta title" name="meta_title" />
                
                <label >Meta description</label>
                <textarea class="form-control" placeholder="Service Meta description" name="meta_description" rows="3"></textarea>
            </div>
        </div>
    </div>
</div>


<div class="form-group mt-3">
    <label class="form-label">
        <input type="radio" class="status" name="status" value="1"> <span></span>
        <span>Published</span>
    </label>
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>

<script>
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output =( document.getElementById)('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };
</script>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/season/form.blade.php ENDPATH**/ ?>