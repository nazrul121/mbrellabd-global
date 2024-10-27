@extends('layouts.app')

@section('title','408 - Page data exprired')

@section('content')
    <div class="error-page mt-100 mb-100">
        <div class="container">
            <div class="error-content text-center pb-5">
                <div class="error-img mx-auto">
                    <img src="https://cdn.dribbble.com/users/2289739/screenshots/6480565/expired-02.jpg" alt="error">
                </div>
                <p class="error-subtitle">Data expired</p>
                <p> We can't find the page you're lokking for Or may the data duration is expired</p>
                <a href="{{url('/')}}" class="btn-primary mt-4">BACK TO HOMEPAGE</a> 
            </div>
        </div>
    </div>
@endsection
