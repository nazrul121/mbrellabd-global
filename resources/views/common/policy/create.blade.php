
@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Create new <b>{{ Request::segment(5) }}</b></h5>
                <div class="card-header-right">
                   @if(check_access('view-policy'))
                    <a href="{{ route('common.policy',$policy_type->slug) }}" class="btn btn-outline-primary addModal"><i class="feather icon-list"></i> View policies</a>
                   @endif
                </div>
            </div>

            <div class="card-body">
                @if (Session::has('message'))
                    <div class="alert @if(Session::has('success')) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
                        <h5> <strong> @if(Session::has('success'))Success :  {{ Session::get('success') }}
                        @else Warning: {{ Session::get('alert') }}  @endif
                        </strong> </h5>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                @endif
                <form id="addForm" class="container" action="{{ route('common.policy.create',$policy_type->id) }}" method="post" enctype="multipart/form-data"> @csrf
                    @include('common.policy.form')

                    <button type="submit" class="btn btn-primary float-right"> <i class="feather icon-save"></i>  Save Data</button>

                </form>

            </div>
        </div>
    </div>
</div>
@endsection

