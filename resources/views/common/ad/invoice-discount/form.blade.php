<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <div class="form-group">
                <label for="recipient-name" class="col-form-label">Discount title</label>
                <input type="text" class="form-control" name="title" required>
            </div>
        </div>

        <div class="col-md-12">
            <div class="row">
                <div class="col-md-8">
                    <label for="photo" class="mt-2">Promotion Photo - <small>Optional</small></label> <br>
                    <div class="input-group mb-3">
                        <div class="custom-file">
                            <input type="file" class="custom-file-input form-control file" name="photo" accept="image/*" onchange="loadFile(event)" >
                            <label class="custom-file-label" for="photo">Choose file</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <img id="output" class="setPhoto" src="{{ url('storage/images/thumbs_photo.png') }}" style="height:90px;max-width:100%">
                </div>
            </div>
        </div>

        <div class="col-12 mb-5 text-center">
            <input type="number" name="width" placeholder="photo width" value="2500">
            <input type="number" name="height" placeholder="photo height" value="400">
        </div>

        <div class="col-md-12 mb-3">
            <label for="recipient-name" class="col-form-label">Discount Type</label>
            <select name="type" class="form-control">
                <option value="general">General Discount</option>
                <option value="product">Product Discount</option>
                <option value="free-delivery">Free of Delivery charge</option>
            </select>
        </div>
        
        <div class="col-md-12">
            <label for="recipient-name" class="col-form-label">Minimum order amount</label>
            <div class="input-group">
                <input type="number" class="form-control" name="min_order_amount" placeholder="Min. order amount" required>
                <div class="input-group-append">
                    <span class="input-group-text percentSymbol">TK</span>
                </div>
            </div>

        </div>
    </div>

    

    <div class="card-block mb-3 mt-2">
        <label for="recipient-name" class="col-form-label">Promotion dates</label>
        <div class="input-daterange input-group" id="datepicker_range">
        <input type="text" class="form-control text-left" placeholder="Start date" name="start_date" required/>

        <input type="text" class="form-control text-right" placeholder="End date" name="end_date" required/>
        </div>
    </div>

    {{-- <div class="row">
        <div class="col-md-6"><input type="text" class="time form-control" name="start_time" required></div>
        <div class="col-md-6"><input type="text" class="time form-control" name="end_time" required></div>
    </div> --}}
    
    <div class="form-group bg-light p-2">
        <p class="text-info">Country for--</p>
        @foreach (get_currency() as $item)
            <label class="form-label">
                <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="{{$item->id}}"> <span></span>
                <span> <img class="flag" style="height:10px;" src="{{ url($item->flag) }}"> {{$item->short_name}}</span>
            </label> &nbsp; &nbsp; 
        @endforeach
    </div>
    


    <div class="col-md-12 productArea" style="display:none">
        <div class="form-group">
            <label for="recipient-name" class="col-form-label">Search product</label>
            <input type="text" class="form-control" name="searchProduct">
        </div>
    </div>

    <div class="row mt-4 generalArea">
        <div class="col-sm-12 mb-2">
            <label class="form-label" >
                <input type="radio" class="discount_in" name="discount_in" value="percent" checked>
                <span>Discount in Percent</span>
            </label> &nbsp; &nbsp;
            <label class="form-label">
                <input type="radio" class="discount_in" name="discount_in" value="amount">
                <span>Discount in Fix amount</span>
            </label>
        </div>

        <label for="" class="col-sm-3 text-right col-form-label">Amount</label>
        <div class="col-sm-9">
            <div class="input-group">
                <input type="number" class="form-control" name="discount_value" placeholder="Discount percentage">
                <div class="input-group-append">
                <span class="input-group-text percentSymbol">%</span>
                </div>
            </div>
        </div>

    </div>

</div>


{{-- <label for="allow_promo_item"> <input type="checkbox" class="form-control" id="allow_promo_item" style="width: 15px;float: left;height: 15px;margin-top: 3px;margin-right: 8px;">
    Not applicable if order got any promotional product item</label> --}}



<div class="form-group mt-4">
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

    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output =( document.getElementById)('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };
  </script>
@endpush
