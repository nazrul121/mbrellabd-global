
    <input type="hidden" name="id">
    <div class="input-field">
        <label class="active">Vairation</label>
        <select name="variation_id" class="form-control">
            <option value="">Choose one</option>
            @foreach ($variation_options as $vo)
                <option value="{{ $vo->variation_id.'|'.$vo->id }}">{{ $vo->title }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-group">
        <div class="row">
            <div class="col-md-8">
                <label class="active">Upload Photo [single]</label>
                <input type="file" class="form-control" name="photo">
            </div>
            <div class="col-md-4 text-center ">
                <img id="output" class="setPhoto mt-4" src="{{ url('storage/images/thumbs_photo.png') }}" style="height:60px;max-width:100%">
            </div>
        </div>
    </div>

