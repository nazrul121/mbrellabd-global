<P class="alert alert-info">Shipping information</P>

<div class="form-group row mt-3">
    <label for="staticEmail" class="col-sm-2 col-form-label">Email - (optional)</label>
    <div class="col-sm-10">
        <input type="text" class="form-control checkShipping" name="shipping_email" data-field="email" value="{{ old('email') }}">
    </div>
</div>

<div class="form-group row mt-3">
    <label for="staticEmail" class="col-sm-2 col-form-label">Phone <span class="text-danger">*</span></label>
    <div class="col-sm-10">
        <input type="text" class="form-control checkShipping" name="shipping_phone" data-field="phone" value="{{ old('phone') }}">
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="col-form-label col-md-4">First Name <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="shipping_fname" class="form-control" value="{{ old('fname') }}">
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">

            <label class="col-form-label col-md-4">Last Name <span class="text-danger">*</span></label>
            <div class="col-md-8">
                <input type="text" name="shipping_lname" class="form-control" value="{{ old('lname') }}" >
            </div>
        </div>
    </div>
</div>



<div class="form-group row mt-4 mb-2">
    <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Country <span class="text-danger">*</span></label>
    <div class="col-sm-4">
        <select name="country" class="form-control">
            <option value="1">Bangladesh</option>
        </select>
    </div>
    <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Divisioin <span class="text-danger">*</span></label>
    <div class="col-sm-4">
        <select name="shipping_division" class="form-control">
            <option value="">Choose</option>
            @foreach (\DB::table('divisions')->where(['country_id'=>'2', 'status'=>'1'])->get() as $div)
                <option @if(old('division')==$div->id)selected @endif value="{{ $div->id.'|'.$div->name }}">{{ $div->name }}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-6">
       <div class="row">
        <label for="staticEmail" class="col-sm-4 col-form-label text-md-star text-md-end">District <span class="text-danger">*</span></label>
        <div class="form-group col-sm-8">
            <select name="shipping_district" class="form-control">
                <option value="">Choose</option>

            </select>
        </div>
       </div>
    </div>
    <div class="col-md-6">
        <div class="row">
            <label for="staticEmail" class="col-sm-4 col-form-label text-md-star text-md-end">Cities <span class="text-danger">*</span></label>
            <div class="form-group col-sm-8">
                <select name="shipping_city" class="form-control" >
                    <option value="">Choose</option>

                </select>
            </div>
        </div>
    </div>
</div>

<div class="form-group mt-3">
    <div class="row">
        <label for="staticEmail" class="col-sm-2 col-form-label text-md-star text-md-end">Address <span class="text-danger">*</span></label>
        <div class="form-group col-sm-10">
            <textarea name="shipping_address" class="form-control" rows="2">{{ old('shipping_address') }}</textarea>
        </div>
    </div>
</div>

