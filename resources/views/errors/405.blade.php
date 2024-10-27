@extends('layouts.app')

@section('content')
    <div class="error-page mt-100">
        <div class="container mb-3">
            <div class="error-content text-center">
                <div class="error-img mx-auto">
                    <img src="https://blog.hubspot.com/hubfs/405-method-not-allowed.jpg" alt="error">
                </div>
                <p class="error-subtitle">Method Not Allowed</p>
                <p> <b>Oops!</b> The method you tried to use isn't allowed. Please check your request and try again.</p>
                <a href="{{url('/')}}" class="btn-primary mt-4">BACK TO HOMEPAGE</a>
            </div>
        </div>
    </div>
@endsection
