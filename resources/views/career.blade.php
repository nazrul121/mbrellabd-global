@extends('layouts.app')

@php
    $metas = \DB::table('metas')->where('pageFor','career');
    $meta = \DB::table('metas')->where(['pageFor'=>'career', 'type'=>'title']);

    $metaTitle = 'Career | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }
@endphp

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush

@section('title', $metaTitle)

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
            <li>Career</li>
        </ul>
    </div>
</div>


<div class="article-page mt-100">
    <div class="container mt-5 mb-5">
        @foreach ($careers as $item)
            <?php $description = str_replace('&amp;', '', strip_tags(str_replace('&nbsp;','',$item->description))); ?>
            <div class="row pb-md-3 pb-sm-3">
                <div class="col-md-12 p-4 mb-2 border border-warning">
                    <header class="jumbotron">
                        <a href="/career-job/{{$item->slug}}"><h1>{{$item->title}}</h1></a>
                        <p>Deadline: {{date('d M, Y',strtotime($item->last_date))}}</p>
                    </header>

                    @if (strlen($description) >120)
                        {{ mb_substr($description, 0, 120)}}
                    @else
                        {{$description}}
                    @endif
                    <a href="{{ route('career-job',[app()->getLocale(), $item->slug])}}" class="btn-primary float-end">Apply Now</a>
                </div>
            </div>
        @endforeach

        
        @if ($careers->count()<1)
            <p class="text-center p-md-5 text-warning border border-warning">No <b>Job post</b> is activated now. Please check after some while!</p>
        @endif
        
    </div>
</div>

@endsection


