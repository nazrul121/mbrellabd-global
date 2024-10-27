@extends('layouts.app')

@section('content')
    <div class="error-page mt-100">
        <div class="container mb-3">
            <div class="error-content text-center">
                <div class="error-img mx-auto">
                    <img src="/assets/img/error/paymentError.jpg" alt="page not found">
                </div>
               
                
                {{-- Display the cause of the error --}}
                <h1>{{ $cause ?? 'Error' }}</h1>

                {{-- Display the message --}}
                <p class="text-danger"><b>Oops!</b> {{ $message ?? 'Something went wrong.' }}</p>

                {{-- Display the order link if available --}}
                @if (!empty($orderLink))
                    <a href="{{ $orderLink }}" class="btn-primary mt-4">Your Order Details</a>
                @else
                    <a href="{{ url('/') }}" class="btn-primary mt-4">BACK TO HOMEPAGE</a>
                @endif
            </div>
        </div>
    </div>
@endsection
