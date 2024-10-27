@extends('layouts.error')

@section('title','505 - Internal server error')

@section('content')
<div class="error-page">
    <div class="container">
        <div class="error-content text-center pb-5">
            <h1 class="error-code">500</h1>
            <div class="error-img mx-auto">
                <img style="max-width:100%"  src="/assets/img/error/500.png" alt="Internal Servicer Error">
            </div>

            <h2 class="error-message">Oops! Internal Server Error</h2>
            
            <p class="mt-4"><a href="{{url('/')}}" class="btn btn-success mt-4">Return to Homepage</a></p>
            {{-- {{ \Request::getRequestUri() }} --}}
        </div>
    </div>
</div>
@endsection
