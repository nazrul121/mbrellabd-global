
<div class="form-group">
    <label for="recipient-name" class="col-form-label">Company Name</label>
    <input type="text" class="form-control" name="company_name">
</div>


<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Company Logo - <small>Optional</small> - [350x350px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="logo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" style="height:100px;max-width:100%">
        </div>
    </div>
</div>

<div class="form-group row">
    <div class="col-md-4">
        <label for="recipient-name" class="col-form-label">Division</label>
        <select name="division" class="div" style="width:100%">
            <option value="">Choose one</option>
            @foreach (\DB::table('divisions')->get() as $div)
            <option value="{{ $div->id }}">{{ $div->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label for="recipient-name" class="col-form-label">District</label>
        <select name="district" style="width:100%">
            <option value="">Choose one</option>
        </select>
    </div>
    <div class="col-md-4">
        <label for="recipient-name" class="col-form-label">City</label>
        <select name="city" style="width:100%">
            <option value="">Choose one</option>
        </select>
    </div>
</div>


<div class="form-group">
    <label for="message-text" class="col-form-label">Company Location/Address</label>
    <textarea class="form-control" name="address" rows="2"></textarea>
</div>


{{--

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
</div> --}}

