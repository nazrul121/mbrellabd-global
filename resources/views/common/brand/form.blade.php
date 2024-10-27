<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"> <h5>Name and Location</h5> </div>
            <div class="card-block">
                <div class="row form-group">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">Brand name</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" value="{{ old('title')??$brand->title }}">
                        <span class="text-danger">{{ $errors->first('title')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message-text" class="col-form-label">Location / Address</label>
                    <textarea class="form-control" rows="5" name="location">{{ old('location')??$brand->location }}</textarea>
                    <span class="text-danger">{{ $errors->first('location')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"> <h5>Brand Logo - <small>Optional</small> - [300x300px]</h5> </div>
            <div class="card-block">
                <div class="form-group">
                    <input type="file" class="dropify" data-height="160" name="photo" data-default-file="{{ url('storage/'.$brand->photo) }}" accept="image/*">
                </div>

                <div class="form-group text-right">
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="1" @if($brand->status=='1')checked @endif> <span></span>
                        <span>Publish Now</span>
                    </label>
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="0" @if($brand->status=='0')checked @endif>
                        <span></span><span>Publish later</span>
                    </label>
                    <span class="text-danger"> &nbsp; &nbsp;  {{ $errors->first('status')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

    <script>
        $(function(){
            $('.dropify').dropify();
        })
    </script>
@endpush
