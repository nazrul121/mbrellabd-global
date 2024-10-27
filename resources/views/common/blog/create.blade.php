
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Blog post entry form</h5>
                <div class="card-header-right">
                    @if(check_access('view-blog'))
                    <a href="{{ route('common.blogs') }}" class="btn btn-outline-primary"><i class="feather icon-list"></i> Blog view</a>  @endif

                </div>
            </div>

            <form class="card-body" method="post" enctype="multipart/form-data" action="{{ route('common.blog.save') }}"> @csrf
                @if (Session::has('message'))
                    <div class="alert @if(Session::has('success')) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
                        <h5> <strong> @if(Session::has('success'))Success :  {{ Session::get('success') }}
                        @else Warning: {{ Session::get('alert') }}  @endif
                        </strong> </h5>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                    </div>
                @endif

                @include('common.blog.form')

                <button type="submit" class="btn btn-primary float-right"> <i class="feather icon-save"></i>  Save Data</button>
            </form>
        </div>
    </div>

</div>
@endsection


