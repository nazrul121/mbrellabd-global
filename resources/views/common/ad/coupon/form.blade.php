<div class="form-group">
    <label for="recipient-name" class="col-form-label">Coupon Type</label>
    <select class="form-control" name="type">
        @foreach ($coupon_types as $type)
        <option value="{{ $type->id }}">{{ $type->title }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Coupon title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="row">
    <div class="form-group col-sm-6">
        <label for="recipient-name" class="col-form-label">Coupon code</label>
        <input type="text" class="form-control" name="coupon_code">
    </div>

    <div class="form-group col-sm-6">
        <label for="recipient-name" class="col-form-label">Initional Cost</label>
        <input type="number" class="form-control" name="cost">
    </div>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Expiry date</label>
    <div class="card-block">
        <input type="text" class="form-control" id="d_auto" name="expiry_date">
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
