<div class="form-group">
    <label for="recipient-name" class="col-form-label">Child-category Name</label>
    <input type="text" class="form-control" name="title">
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Display Name - <small class="text-success">Title shows on category product</small></label>
    <input type="text" class="form-control" name="display_name">
</div>

<div class="form-group">
    <label for="">Choose Main category</label>
    <select class="form-control" id="cat" name="category">
        <option value="">Choose Category</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->title }}</option>
        @endforeach
    </select>
</div>
<div class="form-group">
    <label for="">Choose Sub category</label>
    <select class="form-control" name="sub_category" id="subCatFormField"> </select>
</div>
<div class="form-group">
    <div class="row">
        <div class="col-md-7">
            <label for="photo" class="mt-2">Category Photo - <small>Optional</small> - [210x210px]</label> <br>
            <div class="input-group mb-3">
                <div class="custom-file">
                    <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                    <label class="custom-file-label" for="photo">Choose file</label>
                </div>
            </div>
        </div>
        <div class="col-md-5 text-center">
            <img id="output" class="setPhoto" src="/storage/images/thumbs_photo.png" style="height:100px">
        </div>
    </div>
</div>
<div class="form-group">
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5"></textarea>
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
