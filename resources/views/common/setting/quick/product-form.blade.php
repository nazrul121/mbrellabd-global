<?php
    $product_width =  \App\Models\Setting::where('type','product-weight')->pluck('value')->first();
    $product_height =  \App\Models\Setting::where('type','product-height')->pluck('value')->first();
    $product_watermark =  \App\Models\Setting::where('type','product-watermark')->pluck('value')->first();
?>


<div class="row">
    <form class="col-md-12 text-right" action="{{ route('common.quick-product') }}"> @csrf
        <div class="form-group row">
            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Photo Weight</label>
            <div class="col-sm-9">
                <div class="input-group mb-3">
                    <input type="number" class="form-control form-control-sm" name="product_weight" value="{{ $product_width }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">PX</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Photo Height</label>
            <div class="col-sm-9">
                <div class="input-group mb-3">
                    <input type="number" class="form-control form-control-sm" name="product_height" value="{{ $product_height }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">PX</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group row">
            <label for="colFormLabelLg" class="col-sm-3 col-form-label col-form-label-lg"></label>
            <div class="col-sm-9">
                <div class="form-group">
                    <div class="checkbox checkbox-primary d-inline">
                        <input type="checkbox" name="product_watermark" id="checkbox-p-1" value="{{ $product_watermark }}"  @if($product_watermark==1) checked @endif >
                        <label for="checkbox-p-1" class="cr">Upload <b>product</b> photos with Watermark</label>
                    </div>
                </div>
            </div>
        </div>

        <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update Setting</button>
    </form>
</div>

