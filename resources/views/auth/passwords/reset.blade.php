@extends('layouts.app')

@section('content')
<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="/">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Reset Password</li>
        </ul>
    </div>
</div>

<div class="login-page mt-100" id="formArea">
    <div class="container">
        <form method="POST" action="{{ route('password.update') }}" class="login-form common-form mx-auto mb-5 border border-info"> @csrf
            
            <input type="hidden" name="token" value="{{ $token }}">

            <div class="section-header mb-3">
                <h2 class="section-heading text-center">{{ __('Reset Password') }}</h2>
            </div>
                     
            @if(session('login'))
                <div class="alert alert-info">
                    {{ session('login') }}
                </div>
            @elseif($errors->has('email'))
                <div class="alert alert-danger">
                    {{ $errors->first('email') }}
                </div>
            @endif

            <div class="form-group row pb-3">
                <label >{{ __('E-Mail Address') }}</label>

                <div class="col-md-12">
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row pb-3">
                <label for="password" >{{ __('Password') }}</label>

                <div class="col-md-12">
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row pb-3">
                <label for="password-confirm">{{ __('Confirm Password') }}</label>

                <div class="col-md-12">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                </div>
            </div>

            <div class="form-group row mb-0">
                <div class="col-md-12 pt-1">
                    <button type="submit" class="btn-primary d-block mt-2 btn-signin">
                        {{ __('Reset Password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection


