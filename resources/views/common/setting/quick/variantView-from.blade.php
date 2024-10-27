<?php
    $color_view=  \App\Models\Setting::where('type','color-view')->pluck('value')->first();
?>

<form class="row" action="{{ route('common.quick-colorView') }}"> @csrf
    <div class="col-md-7">
        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="colorView1" name="color_view" @if($color_view=='circle') checked @endif value="circle"class="custom-control-input">
            <label class="custom-control-label" for="colorView1">Circle View</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline">
            <input type="radio" id="colorView2" name="color_view" @if($color_view=='square') checked @endif value="square" class="custom-control-input">
            <label class="custom-control-label" for="colorView2">Square view</label>
        </div>
    </div>
    <div class="col-md-5"> <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update </button></div>

</form>
