<?php $catView=  \App\Models\Setting::where('type','cat-view')->pluck('value')->first();?>

<form class="row" action="{{ route('common.quick-catView') }}"> @csrf
    <div class="col-md-8">
        <div class="custom-control custom-radio custom-control-inline mb-3">
            <input type="radio" id="categorySquare" name="cat_view" value="square" @if($catView=='square')checked @endif class="custom-control-input">
            <label class="custom-control-label" for="categorySquare">Category slider <code>square</code> view</label>
        </div>

        <div class="custom-control custom-radio custom-control-inline mb-2">
            <input type="radio" id="categoryCircle"  name="cat_view" value="circle" @if($catView=='circle')checked @endif class="custom-control-input">
            <label class="custom-control-label" for="categoryCircle">Category slider <code>circle</code> view</label>
        </div>
    </div>
    <div class="col-md-4"> <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Update</button></div>

</form>
