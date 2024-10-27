<?php
    $slider_width =  \App\Models\Setting::where('type','slider-weight')->pluck('value')->first();
    $slider_height =  \App\Models\Setting::where('type','slider-height')->pluck('value')->first();
?>


<form class="text-right row" action="{{ route('common.quick-product') }}"> @csrf
    <div class="col-md-4">
        <div class="form-group row">
            <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Slider Weight</label>
            <div class="col-sm-7">
                <div class="input-group mb-3">
                    <input type="number" class="form-control form-control-sm" name="slider_weight" value="{{ $slider_width }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">PX</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group row">
            <label for="colFormLabelSm" class="col-sm-4 col-form-label col-form-label-sm">Slider Height</label>
            <div class="col-sm-7">
                <div class="input-group mb-3">
                    <input type="number" class="form-control form-control-sm" name="slider_height" value="{{ $slider_height }}">
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">PX</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="col-md-3"><button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update Setting</button></div>
</form>
