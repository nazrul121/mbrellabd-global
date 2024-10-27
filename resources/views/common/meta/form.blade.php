@php
    $pages = ['home','products','career','order-placed','blog', 'invoice', 'wishlist','my-cart','login','register','check-out','showroom', 'faq','track','categories'];
    $types = ['title','keywords','description']
@endphp


<div class="form-group row">
    <label for="name" class="col-sm-3 col-form-label text-md-right">Meta page </label>
    <div class="col-sm-9">
        <select  class="form-control" name="pageFor">
            @foreach ( $pages as $page)
                <option value="{{$page}}">{{$page}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="form-group row">
    <label for="name" class="col-sm-3 col-form-label text-md-right">Meta Type </label>
    <div class="col-sm-9">
        <select  class="form-control" name="keywords">
            @foreach ( $types as $type)
                <option value="{{$type}}">{{$type}}</option>
            @endforeach
        </select>
    </div>
</div>

<div class="bg-default p-3">
    <div class="form-group row">
        <div class="col-sm-12">
            <label>Meta description</label>
            <textarea class="form-control" placeholder="Service Meta description" name="description" rows="3"></textarea>
        </div>
    </div>
</div>
