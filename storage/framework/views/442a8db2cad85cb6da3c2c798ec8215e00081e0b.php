


    <input type="hidden" name="promotion_type" value="<?php echo e($promotion_type->id); ?>">

    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Promotion title</label>
        <input type="text" class="form-control" name="title" required>
    </div>

    <div class="card-block mt-3">
        <label for="recipient-name" class="col-form-label">Promotion dates</label>
        <div class="input-daterange input-group" id="datepicker_range">
        <input type="text" class="form-control text-left" placeholder="Start date" name="start_date" required/>

        <input type="text" class="form-control text-right" placeholder="End date" name="end_date" required/>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6"><input type="text" class="time form-control" name="start_time" required></div>
        <div class="col-md-6"><input type="text" class="time form-control" name="end_time" required></div>
    </div>


    <div class="form-group mt-3">
        <div class="row">
            <div class="col-md-8">
                <label for="photo" class="mt-2">Promotion Photo - <small>Optional</small> - [885x430px]</label> <br>
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                        <label class="custom-file-label" for="photo">Choose file</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-center">
                <img id="output" class="setPhoto" src="<?php echo e(url('storage/images/thumbs_photo.png')); ?>" style="height:90px;max-width:100%">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Background Color</label>
                <input type="color" class="form-control" name="bg_color" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Text Color</label>
                <input type="color" class="form-control" name="text_color" required>
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
        </label> &nbsp;
        <label class="form-label">
            <input type="radio" class="status" name="status" value="0">
            <span></span><span>Unpublished</span>
        </label>
    </div>
    <div class="form-group">
        <label class="form-label">
            <input type="radio" class="expire_visibility" name="expire_visibility" value="show"> <span></span>
            <span>Show promotion expiry date</span>
        </label> &nbsp;
        <label class="form-label">
            <input type="radio" class="expire_visibility" name="expire_visibility" value="hide">
            <span></span><span>Hide expiry date</span>
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
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/ad/promotion/form.blade.php ENDPATH**/ ?>