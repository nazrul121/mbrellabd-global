@extends('layouts.app')

@section('content')

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                    </g>
                </svg>
            </li>
            <li>Size guides</li>
        </ul>
    </div>
</div>


<div class="checkout-page mt-100">
    <div class="container">
        <div class="checkout-page-wrapper">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-12">
                    <div class="elementor-section elementor-top-section elementor-element elementor-element-26bc74a elementor-section-full_width elementor-section-height-default elementor-section-height-default">
                        <div class="row mt-4">
                            <?php $sizeChirt = \DB::table('general_infos')->where('field','size-chirt')->pluck('value')->first();?>
                            @if($sizeChirt)
                                <iframe width="100%" height="800" src="/{{ $sizeChirt }}" frameborder="0"></iframe>
                            @else
                                <div class="cart-total-area">
                                    <div class="cart-total-box mt-4">
                                        <div class="d-flex justify-content-center mt-1 p-2 text-primary">
                                            No size guide uploaded for all products. <br/> Size-guides are product dependent
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('style')
<link href="{{asset('assets/css/bootstrap.min.css')}}" rel="stylesheet">
@endpush
