@extends('layouts.app')

@php
    $metas = \DB::table('metas')->where('pageFor','faq');
    $meta = \DB::table('metas')->where(['pageFor'=>'faq', 'type'=>'title']);

    $metaTitle = 'FAQs | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }
@endphp

@section('title',$metaTitle)

@section('content')

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush


<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li><a>FAQs</a></li>
        </ul>
    </div>
</div>


<div class="faq-section mt-100 overflow-hidden">
    <div class="faq-inner">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-heading">Frequently Asked Question</h2>
            </div>
            <div class="faq-container mb-5">
                <div class="row pb-4">
                    @foreach ($posts as $key=>$faq)
                    <div class="col-lg-6 col-md-6 col-12">
                        <div class="faq-item rounded">
                            <h2 class="faq-heading heading_18 collapsed d-flex align-items-center justify-content-between" data-bs-toggle="collapse" data-bs-target="#faq{{$key}}">
                                {{ $faq->question }}
                                <span class="faq-heading-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#F76B6A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h2>
                            <div id="faq{{$key}}" class="accordion-collapse collapse">
                                <p class="faq-body text_14"> {{ $faq->answer }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @if ($posts->count()<1)
                        <p class="text-center p-md-5 text-warning border border-warning">No <b>FAQ</b>s is activated now. Please check after some while!</p>
                    @endif
                    
                </div>
               
            </div>
        </div>
    </div>
</div>

@endsection
