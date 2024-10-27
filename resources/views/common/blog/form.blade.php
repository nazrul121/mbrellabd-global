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
                        <input type="text" class="form-control" name="title" value="{{ old('title')??$blog->title }}">
                        <span class="text-danger">{{ $errors->first('title')}}</span>
                    </div>
                </div>

                <div class="form-group">
                    <label for="message-text" class="col-form-label">Post details</label>
                    <textarea class="summernote" name="description">{{ old('description')??$blog->description }}</textarea>
                    <span class="text-danger">{{ $errors->first('description')}}</span>
                </div>

                <div class="row form-group">
                    <div class="col-sm-2 text-right">
                        <label class="col-form-label">Blog Category</label>
                    </div>
                    <div class="col-sm-10">
                        <select class="js-example-tags col-sm-12" name="categories[]" multiple="multiple">
                           @foreach($blog_categories as $key=>$cat)
                                <option @if( isset($blog->blog_categories()->get()[$key])) selected @endif value="{{ $cat->id }}">{{ $cat->title }}</option>
                           @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group bg-light p-2">
                    <p class="text-info">Country for--</p>
                    @foreach (get_currency() as $item)
                        @php
                            $check = \DB::table('blog_country')->where(['blog_id'=>$blog->id, 'country_id'=>$item->id]);
                            if($check->count()>0){
                                $isChecked = 'checked';
                            }else $isChecked = '';
                        @endphp
                        <label class="form-label">
                            <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="{{$item->id}}" {{$isChecked}}> <span></span>
                            <span> <img class="flag" style="height:13px;" src="{{ url($item->flag) }}"> {{$item->short_name}}</span>
                        </label> &nbsp; &nbsp; 
                    @endforeach
                    <span class="text-danger">{{ $errors->first('langs')}}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        
        <div class="card">
            <?php $width = \App\Models\Setting::where('type','blog-weight')->pluck('value')->first();
                $height = \App\Models\Setting::where('type','blog-height')->pluck('value')->first();?>
            <div class="card-header"> <h5>Post Photo - <small>Optional</small> - [{{ $height.' x '.$width }} px]</h5> </div>
            <div class="card-block">
                <div class="form-group">
                    <input type="file" class="dropify" data-height="450" name="photo" accept="image/*" data-default-file="{{ url('storage/'.$blog->photo) }}" >
                </div>

                <div class="form-group text-right">
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="1" @if($blog->status=='1')checked @endif> <span></span>
                        <span>Publish Now</span>
                    </label> &nbsp; &nbsp;
                    <label class="form-label">
                        <input type="radio" class="status" name="status" value="0" @if($blog->status=='0')checked @endif>
                        <span></span><span>Publish later</span>
                    </label>
                    <span class="text-danger"> &nbsp; &nbsp;  {{ $errors->first('status')}}</span>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/multi-select/css/multi-select.css">
    <script src="{{ asset('back2') }}/plugins/select2/js/select2.full.min.js"></script>
    <script src="{{ asset('back2') }}/plugins/multi-select/js/jquery.multi-select.js"></script>
    <script src="{{ asset('back2') }}/js/pages/form-select-custom.js"></script>

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
