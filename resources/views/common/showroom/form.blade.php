<div class="form-group">
    <label for="recipient-name" class="col-form-label">Showroom title</label>
    <input type="text" class="form-control" name="title">
</div>


<div class="row">
    <label class="col-sm-3 hsCode text-md-right col-form-label">Contact No</label>
    <div class="col-sm-9">
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="phone">
        </div>
    </div>
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Showroom Photo - <small>Optional</small> - [500px500px]</label> <br>
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

<div class="row">
    <label class="col-sm-3 hsCode text-md-right col-form-label"> Districts</label>
    <div class="col-sm-9">
        <div class="input-group mb-3">
            <select name="district" class="form-control">
                <option value="">Choose district</option>
                @foreach ($districts as $item)
                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Location </label>
    <textarea class="form-control" name="location" rows="2"></textarea>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="4"></textarea>
</div>

<div class="form-group">
    <label for="message-text" class="col-form-label">G-map Embed code (optional) </label>
    <input class="form-control" name="embed_code">
</div>

<iframe class="showEmbed" style="display: none" src="" width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>


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
