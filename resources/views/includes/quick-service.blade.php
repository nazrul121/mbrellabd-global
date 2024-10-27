@php
    $ids = \App\Models\Country_quick_service::where('country_id',session('user_currency')->id)->select('quick_service_id')->distinct()->get()->toArray();
    $quick_services = \DB::table('quick_services')->whereIn('id',$ids)->where('status','1')->select('title','type','type_info','photo','description')->get();
@endphp

@if($quick_services->count()>0)
    <div class="latest-blog-section overflow-hidden home-section mt-5">
        <div class="latest-blog-inner">
            <div class="container">
                <div class="article-card-container position-relative">
                    <div class="common-slider" data-slick='{
                        "slidesToShow":3,  
                        "slidesToScroll": 1,
                        "dots": true, 
                        "arrows": true,
                        "autoplay":true,
                        "autoplaySpeed": 1500,
                        "responsive": [
                            {
                                "breakpoint": 1281, "settings": { "slidesToShow": 2 }
                            },
                            {
                                "breakpoint": 602, "settings": { "slidesToShow": 1}
                            }
                        ]
                        }'>
                        @foreach ($quick_services as $key=>$q)
                            <a href="@if($q->type=='phone')tel: @elseif($q->type=='email')mailto: @else @endif {{ $q->type_info }}">
                                <div class="article-slick-item">
                                    <div class="article-card bg-transparent p-0 shadow-none">
                                        <div class="col-12">
                                        <div class="trusted-badge rounded bg-dark {{$key}}">
                                        <div class="trusted-icon">
                                            <img class="icon-trusted" alt="{{ $q->title }}" src="{{ url('storage').'/'.$q->photo }}">
                                        </div>
                                        <div class="trusted-content">
                                            <h2 class="heading_18 trusted-heading text-white" style="@if(strlen($q->title) >25) font-size:16px; @endif">{{ $q->title }}</h2>
                                            <p class="text_16 trusted-subheading trusted-subheading-3">{{ $q->description }}</p>
                                        </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            @endforeach
                        </a>  
                    </div>
                    
                    <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
                </div>
            </div>
        </div>
    </div>
@endif
