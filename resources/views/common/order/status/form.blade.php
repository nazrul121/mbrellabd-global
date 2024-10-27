<input type="hidden" id="id" name="id" value="{{ old('id') }}">
<div class="form-group">
    <label for="recipient-name" class="col-form-label">Status title</label>
    <input type="text" class="form-control" name="title" required value="{{ old('title') }}">
    <span class="text-danger">{{ $errors->first('title')}}</span>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Status Next Step</label>
    <select class="form-control" name="action" required>
        <option value="">Choose One</option>
        <option @if(old('action')=='stop-action')selected @endif value="stop-action">Stop action here</option>
        <option @if(old('action')=='continue')selected @endif value="continue">Continue Process</option>
    </select>
    <span class="text-danger">{{ $errors->first('action')}}</span>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Order item`s <code>Qty</code> Action</label>
    <select class="form-control" name="qty_status">
        <option value="">Choose One</option>
        <option @if(old('qty_status')=='return-qty')selected @endif value="return-qty">Return Qty into the stock of related item</option>
        <option @if(old('qty_status')=='general')selected @endif value="general">No Action</option>
    </select>
    <span class="text-danger">{{ $errors->first('qty_status')}}</span>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Relational Activity</label>
    <select class="form-control" name="relational_activity">
        <option value="">Choose One</option>
        <option @if(old('relational_activity')=='ask-for-payment')selected @endif value="ask-for-payment">Ask for a payment</option>
		<option @if(old('relational_activity')=='prepare-to-ship')selected @endif value="prepare-to-ship">Prepared for shipment</option>
        <option @if(old('relational_activity')=='ship')selected @endif value="ship">Go for shipment to Courier</option>
        <option @if(old('relational_activity')=='refund')selected @endif value="refund">Refund order price</option>
        <option @if(old('relational_activity')=='return')selected @endif value="return">Return order</option>
        <option @if(old('relational_activity')=='delivered')selected @endif value="delivered">The order completed</option>
        <option @if(old('relational_activity')=='canelled-by-customer')selected @endif value="canelled-by-customer">Cancelled by customer</option>
        <option @if(old('relational_activity')=='canelled-by-author')selected @endif value="canelled-by-author">Cancelled by Authority</option>
        <option value="{{ null }}">No action</option>
    </select>
    <span class="text-danger">{{ $errors->first('relational_activity')}}</span>
</div>

<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5">{{ old('description') }}</textarea>
    <span class="text-danger">{{ $errors->first('description')}}</span>
</div>

