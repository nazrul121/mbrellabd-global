<div class="form-group">
    <label for="recipient-name" class="col-form-label">Courier Company</label>
    <?php $companies = \DB::table('courier_companies')->where('status','1')->get();?>
    <select name="courier_company" id="" class="form-control">
        <option value="">Choose Company</option>
        @foreach ($companies as $company)
            <option value="{{ $company->id }}">{{ $company->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Representative Name</label>
    <input type="text" class="form-control" name="name">
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Phone No</label>
    <input type="text" class="form-control" name="phone">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Photo - <small>Optional</small> - [300x300px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" src="/storage/images/user.jpg" style="height:100px">
        </div>
    </div>
</div>

<div class="form-group">
    <label for="message-text" class="col-form-label">Address</label>
    <textarea class="form-control" name="address" rows="5"></textarea>
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
