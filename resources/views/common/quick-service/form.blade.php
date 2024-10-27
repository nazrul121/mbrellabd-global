<div class="form-group">
    <label for="recipient-name" class="col-form-label">Quick service title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Quick service Photo - <small>Optional</small> - [77x77px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:100px;max-width:100%">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5"></textarea>
</div>

<div class="form-group">
    <label class="form-label">
        <input type="radio" class="type" name="type" value="link" checked />
        <span>Link</span> &nbsp;
    </label>
    <label class="form-label">
        <input type="radio" class="type" name="type" value="phone">
        <span>Call Phone No</span> &nbsp;
    </label>

    <label class="form-label">
        <input type="radio" class="type" name="type" value="email">
       <span>Email service</span> &nbsp;
    </label>
</div>


<div class="form-group">
    <label for="recipient-name" class="col-form-label typeInfoLable">Redirection link</label>
    <input type="text" class="form-control" name="type_info">
</div>

<div class="form-group bg-light p-2">
    <p class="text-info">Country for--</p>
    @foreach (get_currency() as $item)
        <label class="form-label">
            <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="{{$item->id}}"> <span></span>
            <span> <img class="flag" style="height:13px;" src="{{ url($item->flag) }}"> {{$item->short_name}}</span>
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


<script>
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output =( document.getElementById)('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };
</script>
