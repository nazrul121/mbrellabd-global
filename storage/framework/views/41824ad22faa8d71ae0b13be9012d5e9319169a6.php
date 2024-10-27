<div class="form-group">
    <label for="recipient-name" class="col-form-label">campaign title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Campaign Photo - <small>Optional</small> - [800x800px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:80px;max-width:100%">
        </div>
    </div>
</div>


<div class="card-block mt-3">
    <label for="recipient-name" class="col-form-label">Campaign dates</label>
    <div class="input-daterange input-group" id="datepicker_range">
    <input type="text" class="form-control text-left" placeholder="Start date" name="start_date" required/>

    <input type="text" class="form-control text-right" placeholder="End date" name="end_date" required/>
    </div>
</div>

<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5"></textarea>
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
<?php /**PATH /var/www/laravelapp/resources/views/common/ad/campaign/form.blade.php ENDPATH**/ ?>