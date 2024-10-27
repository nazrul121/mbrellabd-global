<div class="row">
    <div class="col-md-6">
        <label for="recipient-name" class="col-form-label">Payment Type</label>
        <?php $paymentTypes = \App\Models\Payment_type::where('status','1')->get();?>
        <select class="form-control" name="payment_type" required>
            <option value="">Choose Type</option>
            @foreach ($paymentTypes as $type)  <option value="{{ $type->id }}">{{ $type->title }}</option> @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label for="recipient-name" class="col-form-label">Amount</label>
        <input type="text" class="form-control" name="amount">
    </div>
</div>

