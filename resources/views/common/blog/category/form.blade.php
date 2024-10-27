<div class="form-group">
    <label for="recipient-name" class="col-form-label">Category title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Category Photo - <small>Optional</small> - [350x350px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:100px;max-width:100%">
        </div>
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
    </label> &nbsp;
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>
