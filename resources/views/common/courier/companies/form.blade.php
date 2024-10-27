<div class="form-group">
    <label for="recipient-name" class="col-form-label">Company Name</label>
    <input type="text" class="form-control" name="name">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Company logo - <small>Optional</small> - [300x300px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:100px">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="message-text" class="col-form-label">Address / Location</label>
    <textarea class="form-control" name="address" rows="5"></textarea>
</div>


<div class="form-group">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group pt-2">
                <p for="">Commission In</p>
                <label class="form-label">
                    <input type="radio" class="commission_in" name="commission_in" value="percentage"> <span></span>
                    <span>Percentage</span>
                </label>
                <label class="form-label">
                    <input type="radio" class="commission_in" name="commission_in" value="fix_amount">
                    <span></span><span>Fix Amount</span>
                </label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <p for="">Amount</p>
                <input type="number" class="form-control" name="amount" placeholder="Commision amount">
            </div>
        </div>
    </div>
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
