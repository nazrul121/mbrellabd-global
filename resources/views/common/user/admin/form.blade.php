<div class="row">
    <div class="form-group col-sm-6">
        <label for="recipient-name" class="col-form-label">First Name</label>
        <input type="text" class="form-control" name="first_name">
    </div>

    <div class="form-group col-sm-6">
        <label for="recipient-name" class="col-form-label">Last Name</label>
        <input type="text" class="form-control" name="last_name">
    </div>
</div>

<input type="hidden" name="is_super" >

<div class="row">
    <div class="col-sm-6">
        <div class="row mt-2 text-right">
            <label for="" class="col-sm-4 mt-1">Position</label>
            <div class="col-md-8">
                <input type="text" style="width:100%" class="" name="position" placeholder="Position">
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="form-group mt-3">
            Gender &nbsp;
            <label class="form-label">
                <input type="radio" class="sex" name="sex" value="male"> <span></span>
                <span>Male</span>
            </label> &nbsp;
            <label class="form-label">
                <input type="radio" class="sex" name="sex" value="female">
                <span></span><span>Female</span>
            </label> &nbsp;
            <label class="form-label">
                <input type="radio" class="sex" name="sex" value="other">
                <span></span><span>Other</span>
            </label>
        </div>
    </div>

</div>


<div class="form-group mb-0">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Admin photo - <small>Optional</small> - [350x350px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" style="height:100px;max-width:100%">
        </div>
    </div>
</div>


<div class="form-group">
    <label for="message-text" class="col-form-label">Address</label>
    <textarea class="form-control" name="address" rows="2"></textarea>
</div>


<div class="form-group mt-3">
    <label for="">Is this admin has <b>Permissin allocation authority</b></label> <br>
    <label class="form-label">
        <input type="radio" class="has_permission" name="has_permission" value="1"> <span></span>
        <span>Yes, this admin is allowed</span>
    </label> &nbsp;
    <label class="form-label">
        <input type="radio" class="has_permission" name="has_permission" value="0">
        <span></span><span>No, this admin is not allowed</span>
    </label> &nbsp;

</div>

@if (Auth::user()->user_type_id==1 && Auth::user()->admin->is_super=='1')
    <div class="form-group">
        <label class="custom-control custom-checkbox">
            <input type="checkbox" name="is_super" class="custom-control-input is_super" >
            <span class="custom-control-label">Set role as <span class="text-info">Super admin</span></span>
        </label>
    </div>
@endif

<div class="row bg-secondary mt-4 userArea text-white">
    <div class="form-group col-md-12">
        <label for="recipient-name" class="col-form-label">Email address</label>
        <input type="email" class="form-control" name="email">
    </div>
    <div class="form-group col-md-12">
        <label for="recipient-name" class="col-form-label">Phone No</label>
        <input type="number" class="form-control" name="phone">
    </div>
    <div class="form-group col-md-12">
        <label for="recipient-name" class="col-form-label">Password</label>
        <input type="text" class="form-control" name="password">
    </div>
</div>

