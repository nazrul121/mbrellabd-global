@extends('layouts.app')

@php

    $metas = \DB::table('metas')->where('pageFor','home');
    $meta = \DB::table('metas')->where(['pageFor'=>'home', 'type'=>'title']);

	$metaTitle = 'Mbrella | A Lifestyle Clothing Brand';
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }

@endphp
@push('meta')
    <meta property="og:title" content="{{ request()->get('title') }}">
    <meta name="description" content="{{ \DB::table('general_infos')->where('field','system_description')->pluck('value')->first() }}" />
   
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
        {{--  <meta property="og:image" content="https://example.com/your-image.jpg">  --}}
    @endforeach

    
    <meta property="og:url" content="{{url()->full()}}">
    
    <meta property="og:type" content="website">

@endpush

@section('title', $metaTitle)

@section('content')


    <!-- slideshow start -->
    @include('includes.slider')

    <?php 
        $invoice = Cache::remember('invoice', 30, function() {
            $ids = \App\Models\Country_invoice_discount::where('country_id', session('user_currency')->id)->select('invoice_discount_id')->distinct()->pluck('invoice_discount_id')->toArray();
            return \App\Models\Invoice_discount::whereIn('id', $ids)->where('status', '1') ->select('title','photo')->orderBy('id', 'DESC') ->first();
        });

    ?>
    @if($invoice !=null) 
    <div class="single-banner-section overflow-hidden">
        <div class="position-relative overlay pt-2 mt-3 text-center">
            <img src="{{url('storage/'.$invoice->photo)}}" alt="{{$invoice->title}}">
            <div class="content-absolute content-slide">
                <div class="container height-inherit d-flex align-items-center">
                    <div class="content-box single-banner-content py-4">
                        {{-- <h2 class="single-banner-heading heading_42 text-white animate__animated animate__fadeInUp" data-animation="animate__animated animate__fadeInUp">
                            {{$invoice->title}}
                        </h2> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @include('includes.promotions')

    {{-- category  --}}
    <div class="loadCategory"></div>

    <div class="loadSubCategory"></div>

    <!-- video start -->
    <div class="loadVideo"></div>

    <!-- highlight start -->
    <div class="loadHighlight"></div> 

  
    @include('includes.instagram-feed')     
   
    <!-- testimonial start -->
    @include('includes.testimonial')

    <!-- latest blog start -->
    <div class="loadBlogs"></div>

    <!-- quick service -->
    @include('includes.quick-service')

    @include('includes.subscribe')

    <!-- welcome modal -->
    @if($promotions->count() >0)
        <div class="modal fade" tabindex="-1" id="startUpModal" aria-modal="false" role="dialog">
            <div class="modal-dialog modal-dialog-centered modal-lg" style="width: fit-content;">
                <div class="modal-content newsletter-modal-content modal-lg">
                    <div class="modal-body">
                        <a href="#close-modal" rel="modal:close" class="close-modal " data-bs-dismiss="modal" aria-label="Close"></a>
                        <a  href="{{ route('promo-items',[app()->getLocale(), $promotions[0]->slug]) }}" >
                            <img src="{{ url('storage/'.$promotions[0]->photo) }}" style="border: 4px solid transparent; border-image: linear-gradient(to bottom, {{$promotions[0]->bg_color}}, {{$promotions[0]->text_color}}); border-image-slice: 1;"> 
                        </a>
                    </div>
                </div>
            </div>
        </div> 
    @endif 


@endsection

@push('style')
    <style>
        .example-marquee { position: relative; }
        .content {z-index: 1; position: relative; }
    </style>
@endpush

@push('scripts')

    <script>
        
        $(document).ready(function() {
            $('.loadVideo').load("{{ route('load-home-video',app()->getLocale()) }}")
            $('.loadBlogs').load("{{ route('load-home-blog', app()->getLocale()) }}")
            $('.loadHighlight').load("{{ route('load-home-highlight', app()->getLocale()) }}")
            $('.loadCategory').load("{{ route('load-home-category', app()->getLocale()) }}")
            $('.loadSubCategory').load("{{ route('load-home-subCategory', app()->getLocale()) }}")
            // var isshow = localStorage.getItem('isshow');
            // if (isshow== null) {
                // localStorage.setItem('isshow', 1);
                $('#startUpModal').modal('show');
            // }

            var elementTop, elementBottom, viewportTop, viewportBottom;

            function isScrolledIntoView(elem) {
                elementTop = $(elem).offset().top;
                elementBottom = elementTop + $(elem).outerHeight();
                viewportTop = $(window).scrollTop();
                viewportBottom = viewportTop + $(window).height();
                return (elementBottom > viewportTop && elementTop < viewportBottom);
            }
        })
    </script>
@endpush
