@extends('layouts.error')

@section('title','404 - Page Not Found')

@section('content')

<div class="error-page">
    <div class="container">
        <div class="error-content text-center pb-5">
            <h1 class="error-code">404</h1>
            <div class="error-img mx-auto">
                <img style="max-width:100%" src="/assets/img/error/404.png" alt="page not found">
            </div>

            <h2 class="error-message">Oops! Page Not Found.</h2>
            
            <p class="mt-4"><a href="{{url('/')}}" class="btn btn-success mt-4">Return to Homepage</a></p>
        </div>
    </div>
</div>
@endsection
