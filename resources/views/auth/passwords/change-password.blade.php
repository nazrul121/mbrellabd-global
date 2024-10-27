@extends('layouts.app')

@section('content')

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="/">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                    </g>
                </svg>
            </li>
            <li>{{ __('Change Password') }}</li>
        </ul>
    </div>
</div>


<div class="login-page mt-100" id="formArea">
    <div class="container">

        <form class="login-form common-form mx-auto" action="{{ route('save-password') }}" method="post">@csrf
            <div class="section-header mb-3">
                <h2 class="section-heading text-center">{{ __('Change Password') }}</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <fieldset>
                        <label class="label">Current Password</label>
                        <input type="password" class="" name="old_password" />
                        @error('old_password') <span class="text-danger"> {{ $message }} </span> @enderror
                    </fieldset>
                </div>

                <div class="col-12">
                    <fieldset>
                        <label class="label">New Password</label>
                        <input type="password"  class="" name="password" />
                        @error('password') <span class="text-danger"> {{ $message }} </span> @enderror

                    </fieldset>
                </div>

                <div class="col-12">
                    <fieldset>
                        <label class="label">Confirm New Password</label>
                        <input type="password"  class="" name="password_confirmation" />
                        @error('password_confirmation') <span class="text-danger"> {{ $message }} </span> @enderror

                    </fieldset>
                </div>

             
                <div class="col-12 mt-3">
                    <button type="submit" class="btn-primary d-block mt-2 btn-signin">Change Password</button>
                </div>

                
            </div>
        </form>
    </div>
</div> 
@endsection
