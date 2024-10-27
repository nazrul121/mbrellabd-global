<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"> <h5>Page post details</h5> </div>
            <div class="card-block">
                <div class="row form-group">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">Post title</label>
                    </div>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" name="title" value="{{ old('title')??$page_post->title }}">
                        <span class="text-danger">{{ $errors->first('title')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message-text" class="col-form-label">Post details</label>
                    <textarea class="summernote" name="description">{{ old('description')??$page_post->description }}</textarea>
                    <span class="text-danger">{{ $errors->first('description')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-header"> <h5>Post Photo - <small>Optional</small> - [600x600px]</h5> </div>
            <div class="card-block">
                <div class="form-group">
                    <input type="file" class="dropify" data-height="400" name="photo" accept="image/*" data-default-file="{{ url('storage/'.$page_post->photo) }}" >
                </div>

                <div class="form-group text-right">
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="1" @if($page_post->status=='1')checked @endif> <span></span>
                        <span>Publish Now</span>
                    </label>
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="0" @if($page_post->status=='0')checked @endif>
                        <span></span><span>Publish later</span>
                    </label>
                    <span class="text-danger"> &nbsp; &nbsp;  {{ $errors->first('status')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="bg-light p-3">
    <div class="row">
        <div class="col-md-12 linkField">
            <div class="form-group">
                <label >Meta title</label>
                <input class="form-control" placeholder="Service Meta title" name="meta_title" value="{{$page_post->meta_title}}"/>
                
                <label >Meta description</label>
                <textarea class="form-control" placeholder="Service Meta description" name="meta_description" rows="3">{{$page_post->meta_description}}</textarea>
            </div>
        </div>
    </div>
</div>


<input type="hidden" name="page_post_type_id" value="{{ $page_post_type->id }}">


@push('scripts')
    <script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
    <!-- summernote css/js -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

    <script>
        $(function(){
            $('.dropify').dropify();
            $('.summernote').summernote({ height: 300 });
        })
    </script>
@endpush
