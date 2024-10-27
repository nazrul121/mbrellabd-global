<?php
    $delivery=  \App\Models\Setting::where('type','deliveryCost_from')->pluck('value')->first();
?>

<form class="row" action="{{ route('common.quick-delivery') }}"> @csrf
    <div class="col-md-8">
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="customRadioInline2" name="delivery_cost" @if($delivery=='district') checked @endif value="district"class="custom-control-input">
            <label class="custom-control-label" for="customRadioInline2">District wise Delivery Change</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="customRadioInline3" name="delivery_cost" @if($delivery=='zone') checked @endif value="zone" class="custom-control-input">
            <label class="custom-control-label" for="customRadioInline3">Zone wise Delivery Change</label>
        </div>
    </div>
    <div class="col-md-4"> <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update</button></div>

</form>
