<?php   $size =  \App\Models\Setting::where('type','size-guide')->pluck('value')->first(); ?>


<form class="text-right row" action="{{ route('common.quick-size-guide') }}"> @csrf
    <div class="col-md-4">
        <div class="form-group row">
            <label for="colFormLabelSm" class="col-sm-3 col-form-label col-form-label-sm pt-3">Upload new</label>
            <div class="col-sm-9">
               <input type="file" class="form-control form-control-sm" name="file">
            </div>
        </div>
        <div class="row float-right">
            <button class="btn btn-outline-primary float-right"> <i class="feather icon-edit"></i> Upload size-guide</button>
        </div>
    </div>
    <div class="col-md-8 b-light">
        <embed src="{{ $size }}" width="100%" height="180px" />
    </div>
</form>
