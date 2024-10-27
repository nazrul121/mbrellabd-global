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

<div class="form-group">
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


<div class="row">
    <div class="form-group col-sm-5">
        <label for="recipient-name" class="col-form-label">Employee Dept</label>

        <select name="employee_category" id="" class="form-control">
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group col-sm-7">
        <label for="recipient-name" class="col-form-label">Employee position</label>
        <input type="text" class="form-control" name="position">
    </div>
</div>



<div class="form-group">
    <div class="row mt-3">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Employee Photo - <small>Optional</small> - [350x350px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" src="/storage/images/user.jpg" style="height:100px;max-width:100%">
        </div>
    </div>
</div>


<div class="form-group">
    <label for="message-text" class="col-form-label">Living Address</label>
    <textarea class="form-control" name="address" rows="2"></textarea>
</div>

<div class="form-group row text-right">
    <div class="col-md-2 mt-2"><label for="recipient-name" class="col-form-label">Salary</label></div>
    <div class="col-md-4"> <input type="number" class="form-control" name="salary"> </div>

    <div class="col-sm-6">
        <div class="form-group pt-3">
           <b> Status &nbsp; <i class="feather icon-arrow-right"></i> &nbsp;</b>
            <label class="form-label">
                <input type="radio" class="status" name="status" value="1"> <span></span>
                <span>Published</span>
            </label>
            <label class="form-label">
                <input type="radio" class="status" name="status" value="0">
                <span></span><span>Unpublished</span>
            </label>
        </div>
    </div>
</div>

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

