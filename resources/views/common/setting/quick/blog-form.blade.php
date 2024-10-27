<?php
    $blog_width =  \App\Models\Setting::where('type','blog-weight')->pluck('value')->first();
    $blog_height =  \App\Models\Setting::where('type','blog-height')->pluck('value')->first();
    $blog_watermark =  \App\Models\Setting::where('type','blog-watermark')->pluck('value')->first();
?>

<form class="col-md-12 text-right" action="{{ route('common.quick-blog') }}"> @csrf
    <div class="form-group row">
        <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm">Photo Weight</label>
        <div class="col-sm-9">
            <div class="input-group mb-3">
                <input type="number" class="form-control form-control-sm" name="blog_weight" value="{{ $blog_width }}">
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
                <input type="number" class="form-control form-control-sm" name="blog_height" value="{{ $blog_height }}">
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
                    <input type="checkbox" name="blog_watermark" id="blogwatermark" value="{{ $blog_watermark }}" @if($blog_watermark==1) checked @endif />
                    <label for="blogwatermark" class="cr">Upload <b>blog</b> photos with Watermark</label>
                </div>
            </div>
        </div>
    </div>
    <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update Setting</button>
</form>
