<div class="form-group">
    <label for="recipient-name" class="col-form-label">Question</label>
    <input type="text" class="form-control" name="question">
</div>


<div class="form-group">
    <label for="message-text" class="col-form-label">Answer </label>
    <textarea class="form-control" name="answer" rows="5"></textarea>
</div>

<div class="bg-light p-3">
    <div class="row">
        <div class="col-md-12 linkField">
            <div class="form-group">
                <label >Meta title</label>
                <input class="form-control" placeholder="Service Meta title" name="meta_title" value=""/>
                
                <label >Meta description</label>
                <textarea class="form-control" placeholder="Service Meta description" name="meta_description" rows="3"></textarea>
            </div>
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
    </label> &nbsp;
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>
