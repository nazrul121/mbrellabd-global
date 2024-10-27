@extends('layouts.app')

@php
    $metas = \DB::table('metas')->where('pageFor','showroom')->get();
    $meta = \DB::table('metas')->where(['pageFor'=>'order-placed', 'type'=>'title']);
    
    $metaTitle = 'outlets | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }

@endphp

@section('title',$metaTitle)

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush


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
                <li>Outlets</li>
            </ul>
            <select name="district" class="districtSearach">
                <option value="">Choose district</option>
                @foreach ($districts as $item)
                    <option value="{{ $item->id }}" @if(request()->get('district')==$item->id)selected @endif >{{ $item->name }}</option>
                @endforeach
            </select>
            
        </div>
    </div>


    <?php $key=1;?>

    <div class="banner-section mt-100 overflow-hidden">
        <div class="banner-section-inner">
            <div class="container">
                <div class="row mb-3">
                    @foreach ($showrooms as $key=>$showroom)
                    <div class="col-md-6 col-sm-12" data-aos="fade-up" data-aos-duration="{{$key+660}}">
                        <div class="row m-1 p-2 bg-5">
                            <div class="col-md-4 col-sm-5 mt-3">
                                <a target="_blank" href="{{ url('storage').'/'.$showroom->photo }}">
                                    <img class="p-3" src="{{ url('storage').'/'.$showroom->photo }}" style="max-width:95%"></a>
                            </div>
                            <div class="col-md-8 col-sm-7 mt-md-4 mt-sm-4">
                                <h4>{{ $showroom->title }}</h4>
                                <p>Contact No: {{ $showroom->phone }} <br>
                                Address: {{ $showroom->location }}</p>
                                @if($showroom->embed_code !=null)
                                <button class="p-2 mapView" id="{{ $showroom->id }}"><i class="fa fa-street-view " style="font-size:18px"></i> View on Google Map</button>
                                @endif
                            </div>
                        </div>
                    </div>  <?php $key++;?>
                    @endforeach 
                </div>
            </div>
        </div>
    </div>



    <div class="modal fade" id="trackingModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="preload text-center text-secondary"></div>
                <iframe src="" width="600" height="500" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
<style>
    .districtSearach{
        position: relative;
        right: 0;top: -25px;
        padding: 3px; float: right;
        margin-bottom: -25px;
        display: block;
    }
</style>
    <script>
        $(function(){
            var url = $("#url").val();

            $('.districtSearach').on('change', function(){
                var id = $(this).val();
                var currentUrl = window.location.href;
                var url = new URL(currentUrl);

                // Set or update the parameter in the URL
                url.searchParams.set('district', id);

                // Reload the page with the updated URL
                window.location.href = url;
            })

            $('.mapView').on('click',function(){
                $('.preload').html('<p><br/>Loading Google map. Please wait...<br/></p>');
                $('iframe').css('display','none');
                var id = $(this).attr('id');
                $('#trackingModal').modal('show');
        
                $('.modal').css('margin-top','5%');
              
                setTimeout(() => {
                    $.get(url+"/showroom-map/"+id, function(data, status){
                        $('iframe').attr('src',data);
                        $('.preload').html('');
                        $('iframe').css('display','block');
                    });
                }, 200);
            })

        })
    </script>
@endpush
