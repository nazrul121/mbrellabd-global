<?php
    $status=  \App\Models\Setting::where('type','add-to-cart-status')->pluck('value')->first();
?>

<form class="row" action="{{ route('common.change-addToCart-status') }}"> @csrf
    <div class="col-md-8">
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="addToCartStatu1" name="addToCartStatus" @if($status=='1') checked @endif value="1"class="custom-control-input">
            <label class="custom-control-label" for="addToCartStatu1">Add To Cart <b>Active</b> for customers</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="addToCartStatus2" name="addToCartStatus" @if($status=='0') checked @endif value="0" class="custom-control-input">
            <label class="custom-control-label" for="addToCartStatus2">Add To Cart <b>Inactive</b> for customers</label>
        </div>
    </div>
    <div class="col-md-4"> <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update</button></div>

</form>
