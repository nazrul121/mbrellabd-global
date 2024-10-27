
@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Product entry form </h5>
                <div class="card-header-right">
                    <a href="{{ route('common.product') }}" class="btn btn-outline-primary addModal"><i class="feather icon-list"></i> View products</a>
                </div>
            </div>
        </div>
    </div>
    @if (Session::has('message'))
    <div class="card-body">
        <div class="alert @if(Session::has('success')) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
            <h5> <strong> @if(Session::has('success'))Success :  {{ Session::get('success') }}
               &nbsp; &nbsp; &nbsp; You may add variants now
               <a href="{{ route('common.product') }}?product={{Session::get('id')}}&title={{Session::get('title')}}">Click here</a>
            @else Warning: {{ Session::get('alert') }}  @endif
            </strong> </h5>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
    </div>  @endif

    <form id="addForm" class="col-12" style="position:relative;top:-25px" action="{{ route('common.product.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        @include('common.product.form')
    </form>
</div>
@endsection
