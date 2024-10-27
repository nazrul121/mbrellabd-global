@extends('layouts.app')

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    <meta property="og:title" content="Product categories">
 
@endpush

@section('content')

<div class="page-header">
    <div class="container d-flex flex-column align-items-center">
        <h1>Categories</h1>
    </div>
</div>

<section class="essentials-section">
    <div class="container">
        <h2 class="section-title mb-2 mt-2 text-center">Shop with <b>Product categories</b></h2>

        <div class="row m-b-5">
            @foreach ($categories as $key=>$cat)
            <div class="col-sm-10 offset-sm-1 mb-4 bg-light p-5">
                <div class="widget">
                    <h4 class="widget-title">
                        <a href="/group/{{ $cat->slug }}" target="_blank">  {{ $cat->title }} </a> 
                    </h4>
                    <small>{{ $cat->description }}</small>
                    <ul class="contact-info pt-0">
                        @foreach ($cat->inner_groups()->get() as $inner)
                            <li class="mt-3">
                                <h5 class="mb-0"><a href="/group-in/{{ $inner->slug }}"> <i class="fa fa-plus-square text-warning"></i> {{ $inner->title }}</a></h5>
                            @if($inner->child_groups()->count() >0)
                                <ul class="contact-info pt-0">
                                    @foreach ($inner->child_groups()->get() as $child)
                                        <li class="p-2"><a href="/child-in/{{ $child->slug }}"> &nbsp; &nbsp; <i class="fa fa-arrow-right text-warning"></i> {{ $child->title }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


@endsection

@push('style')
<style>
    .btn-remove-wish {
        position: absolute;  top: -10px; right: -8px;  width: 2rem;
        height: 2rem; border-radius: 50%; color: #474747;
        background-color: #fff;  box-shadow: 0 2px 6px 0 rgb(0 0 0 / 40%);
        text-align: center;  line-height: 2rem;
    }
</style>
<link rel="stylesheet" href="/assets/css/style.min.css">
<style>
    .menu.sf-arrows>li>.sf-with-ul:after, .menu.sf-arrows>li>a:after{background: none}
    .footer .widget-newsletter .form-control{ background:#f4f4f4}
    .footer .social-icon:not(:hover):not(:active):not(:focus){background: #000}
</style>
@endpush
