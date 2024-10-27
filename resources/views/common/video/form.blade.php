<div class="col-md-12">
    <div class="row form-group">
        <label class="col-form-label">Video title</label>
        <input type="text" class="form-control" name="title">
    </div>
</div>

<div class="col-md-12">
    <div class="row form-group">
        <label class="col-form-label">Page for</label>
        <select class="form-control" name="page">
            <option value="home">Video for Home Page</option>
            <option value="gallery">Video for Gallery</option>
        </select>
    </div>
</div>

<div class="form-group">
    <label class="form-label">
        <input type="radio" class="type" name="type" value="link" checked> <span></span>
        <span>Youtube link</span>
    </label> &nbsp; &nbsp;
    <label class="form-label">
        <input type="radio" class="type" name="type" value="video"> <span></span>
        <span>Upload direct video</span>
    </label>
</div>


<div class="form-group">
    <div class="row">
        <div class="col-md-12 videoField" style="display: none">
            <label for="photo" class="mt-2">Product Vidoe - <small>Max-size: [10mb]</small> </label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="file" accept="video/*" />
                    <label class="custom-file-label" for="file">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-12 linkField">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Video <code>Embed code</code></label>
                <input type="text" class="form-control" name="video_link">
            </div>
        </div>

        <div class="col-md-12">
            <iframe class="mt-3" id="newIframe" style="display: none;" width="100%" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>


<div class="form-group bg-light p-2">
    <p class="text-info">Country for--</p>
    @foreach (get_currency() as $item)
        <label class="form-label">
            <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="{{$item->id}}"> <span></span>
            <span> <img class="flag" style="height:10px;" src="{{ url($item->flag) }}"> {{$item->short_name}}</span>
        </label> &nbsp; &nbsp; 
    @endforeach
</div>


<div class="form-group">
    <label class="form-label">
        <input type="radio" class="status" name="status" value="1"> <span></span>
        <span>Published</span>
    </label>
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>

@push('scripts')
    <script>
        $(function(){
            $('[name=type]').on('change',function(){
                let data = $(this).val()
                if(data=='video'){
                    $('.videoField').slideDown(); $('.linkField').slideUp();
                    var videoURL = $('#newIframe').prop('src');
                    videoURL = videoURL.replace("&autoplay=1", "");
                    $('#newIframe').prop('src','');
                    $('#newIframe').prop('src',videoURL);
                    $('#newIframe').slideUp();
                }else{
                    $('.videoField').slideUp();  $('.linkField').slideDown();
                    $('[name=video_link]').val('');
                }
            })

            $('[name=video_link]').keyup(function(){
                let iframe = $(this).val();
                $('#newIframe').css('display','block');
                $('#newIframe').css('height','300px');

                let url = $(iframe).attr('src');
                $('#newIframe').attr('src',url);
                $(this).val(url);
            })
        })
    </script>
@endpush
