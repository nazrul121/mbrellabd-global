<div class="row">
    <div class="col-12">
        <div class="card mb-0">
            <div class="form-group">
                <label class="form-label" for="title">Divisions/Regions</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="District">
            </div>

            <div class="form-group bg-light p-2">
                <p class="text-info">Country for--</p>
                <select name="country" class="form-control">
                    @foreach (get_currency() as $item)
                        <option value="{{$item->id}}" @if($item->id == request()->get('country'))selected @endif> {{$item->short_name}}</option>
                    @endforeach
                </select>
            </div>
            
   
            <div class="form-group">
                <label class="form-label" for="title">Order Delivery change</label>
                <input type="text" class="form-control" name="delivery_cost" placeholder="Order delivery change">
            </div>

            <div class="form-group">
                <label class="form-label" for="title">District office website (optional)</label>
                <input type="text" class="form-control" name="url" placeholder="Website of district office">
            </div>
        </div>
    </div>
</div>
