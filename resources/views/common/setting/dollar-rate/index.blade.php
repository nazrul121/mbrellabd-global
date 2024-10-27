
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Dollar rates for  <b>other</b> currencies</h5>  </div>

            <div class="card-body">
                <form class="col-md-6 offset-md-3" method="post" action="{{ route('common.dollar-store') }}">@csrf
                  
                    @if(session('success'))
                    <div class="p-3 mb-3 alert-success alert-dismissible fade show" role="alert"><strong><i class="fas fa-check"></i></strong> 
                         {{ session()->get('success') }}
                        <a href="?" class="btn-close float-right">x</a>
                    </div> @endif

                    @foreach ($countries as $country)
                        <?php $value = \DB::table('dollar_rates')->where('country_id',$country->id)->pluck('value')->first();?>
                        <div class="input-group mb-3">
                            <span class="input-group-text">$ 1.00 =</span> 
                            <input type="text" class="form-control" id="multiple-addons" name="currency[]" value="{{ $value }}">
                            <input type="hidden" name="country[]" value="{{ $country->id }}">
                            <span class="input-group-text">{{ $country->short_name }} </span> 
                        </div>
                    @endforeach
                    <div class="row float-right">
                        <button type="submit" class="btn btn-success float-md-right"><i class="fas fa-edit"></i> Update <b>Dollar</b> rates</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
