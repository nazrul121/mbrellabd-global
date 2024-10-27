@extends('layouts.app')

@section('title','Search Promotions | '.request()->get('system_title'))

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{request()->get('system_title')}} : Promotion" />
@endpush

@section('content')

    @include('includes.breadcrumb')

    @if($promotions->count() >0)
        @include('includes.promotions') 
    @else
        <div class="cart-page mt-100">
            <div class="container">
                <div class="cart-page-wrapper">
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-12">
                            <div class="cart-total-area">
                                <div class="cart-total-box mt-4">
                                    <p class="shipping_text text-center">No promotions available right now</p>
                                    <div class="d-flex justify-content-center mt-1">
                                        <a href="{{route('products')}}" class="position-relative btn-primary text-uppercase">
                                            Continue Shopping with variants
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
