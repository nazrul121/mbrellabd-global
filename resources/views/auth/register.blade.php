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
                <li>Register</li>
            </ul>
        </div>
    </div>


    <div class="login-page mt-100">
        <div class="container">
            <form action="#" class="login-form common-form mx-auto mb-5" method="POST" action="{{ route('register') }}">@csrf
                <div class="section-header mb-3">
                    <h2 class="section-heading text-center">Register</h2>
                </div>
                <div class="row">
                    @if (Session::has('message'))
                    <div class="alert @if(Session::has('success')) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
                        <h5> <strong> @if(Session::has('success'))Success :  {{ Session::get('success') }}
                            @else Warning: {{ Session::get('alert') }}  @endif
                        </strong> </h5>
                    </div> 
                    @endif
                    <div class="col-12">
                        <fieldset>
                            <label class="label">First Name &nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="@error('first_name') bg-5 @enderror" name="first_name" value="{{ old('first_name') }}"/>
                            @error('first_name')<span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Last Name &nbsp;<span class="text-danger">*</span></label>
                            <input type="text" class="@error('last_name') bg-5 @enderror" name="last_name" value="{{ old('first_name') }}"/>
                            @error('last_name') <span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Mobile No &nbsp;<span class="text-danger">*</span></label>
                            <input type="text"class="@error('phone') bg-5 @enderror" name="phone" value="{{ old('phone') }}"/>
                            @error('phone') <span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

                    <div class="col-12">
                        <fieldset>
                            <label class="label">Email address</label>
                            <input type="email" class="@error('email') bg-5 @enderror" name="email" value="{{ old('email') }}"/>
                            @error('email') <span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

                    <div class="col-12">
                        <fieldset>
                            <label class="label">Address &nbsp;<span class="text-danger">*</span></label>
                            <textarea rows="3" class="form-control @error('address') bg-5 @enderror" name="address">{{ old('address') }}</textarea>
                            @error('address') <span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>

             
                    <div class="col-12">
                        <fieldset>
                            <label class="label">Password</label>
                            <input type="password" name="password" class="@error('password') bg-5 @enderror" />
                            @error('password') <span class="text-danger">{{ $message }}</span>@enderror
                        </fieldset>
                    </div>
                    <div class="col-12 mt-3">
                        <button type="submit" class="btn-primary d-block mt-3 btn-signin">Create Account</button>
                    </div>
                </div>
            </form>
        </div>
    </div> 


@endsection
