
@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Page contents of <b>{{ Request::segment(5) }}</b></h5>
                <div class="card-header-right">
                    <a href="{{ route('common.page-post',$page_post_type->slug) }}" class="btn btn-outline-primary addModal"><i class="feather icon-list"></i> View Posts</a>
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

                <form id="addForm" class="container" action="{{ route('common.page-post.update',$page_post->id) }}" method="post" enctype="multipart/form-data"> @csrf
                    @include('common.page.form')
                    <button type="submit" class="btn btn-primary float-right"> <i class="feather icon-edit"></i>  Update Data</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
