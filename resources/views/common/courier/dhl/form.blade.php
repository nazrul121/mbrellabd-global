
<div class="row form-group">
    <div class="col-sm-3 text-right">
        <label class="col-form-label">From (in kg)</label>
    </div>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="weight_from" required>
    </div>
    <div class="col-md-1 text-right">
        <label class="col-form-label">To</label>
    </div>
    <div class="col-sm-4">
        <input type="text" class="form-control" name="weight_to" required>
    </div>
</div>

@foreach ($countries as $item)
    @php
        $name = '';
        $check = DB::table('countries')->where('zone',$item->zone);
        if ($check->count()>1){
            foreach ($check->get() as $cnt){
                $name .= $cnt->short_name.' ,';
            }
        }else $name = $item->short_name;
    @endphp
<div class="row form-group">
    <div class="col-sm-6">
        <label class="col-form-label">Zone ID</label>
        <input type="text" class="form-control zone{{ $item->zone }}" name="zone[]" value="{{ $item->zone.' - '.$name }}" readonly>
    </div>
    <div class="col-sm-6">
        
        <label class="col-form-label">Price (in USD)</label>
        <input type="text" class="form-control price{{ $item->zone }}" name="price[]" required>
    </div>
</div>


@endforeach

