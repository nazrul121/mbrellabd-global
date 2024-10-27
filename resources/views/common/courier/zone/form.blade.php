<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <div class="form-group">
                <label class="form-label" for="title">Compnay Name</label>
                <?php $companies = \DB::table('courier_companies')->where('status','1')->get();?>
                <select class="form-control" id="company" name="company" required>
                    <option value="">Courier Company</option>
                    @foreach ($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Zone Name</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Zone title">
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Delivery Duration</label>
                <input type="text" class="form-control"  name="duration" placeholder="duration">
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Order Delivery charge</label>
                <input type="text" class="form-control" name="delivery_cost" placeholder="Order delivery charge">
            </div>

            <div class="form-group">
                <label class="form-label" for="title">Order Return charge</label>
                <input type="text" class="form-control" name="return_cost" placeholder="Order retrun charge">
            </div>

            <div class="form-group">
                <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
                <textarea class="form-control" name="description" rows="5"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">
                    <input type="radio" class="status" name="status" value="1">
                    <span></span>  <span>Published</span>
                </label>
                <label class="form-label">
                    <input type="radio" class="status" name="status" value="0">
                    <span></span><span>Unpublished</span>
                </label>
            </div>
        </div>

    </div>
</div>
