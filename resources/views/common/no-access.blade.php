
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Size chirts</h5>
                <div class="card-header-right">
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>
                </div>
            </div>

            <div class="card-body">
                <div class="col-12">
                   <p class="alert alert-danger text-center" style="font-size:18px"> <i class="fa fa-times-square budge"></i> You dont have access over the route</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
