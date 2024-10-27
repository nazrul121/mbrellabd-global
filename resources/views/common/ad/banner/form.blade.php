<div class="form-group">
    <label for="recipient-name" class="col-form-label">Banner title</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-8">
            <label for="photo" class="mt-2">Banner Photo - <small>Optional</small> - [1800x300px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-center">
            <img id="output" class="setPhoto" src="{{ url('storage/images/thumbs_photo.png') }}" style="height:100px;max-width:100%">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Position</label>
    <select class="form-control" name="position">
        <option value="home">Home Page</option>
    </select>
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Redirection link (optional)</label>
    <input type="text" class="form-control" name="link">
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
