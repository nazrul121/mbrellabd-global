<div class="container">
    
    @if($promotions->count() ==1)
    <div class="banner-section mt-3 overflow-hidden mb-3">
        <div class="container" style=" background: linear-gradient(to top, {{$promotions[0]->text_color}},{{$promotions[0]->bg_color}}); ">
            <div class="collection-product-container">
                <center class="p-3">
                    <a href="{{ route('promo-items',[app()->getLocale(),$promotions[0]->slug]) }}">
                    <img  src="{{ url('storage/'.$promotions[0]->photo) }}" class="article-card-img p-1" style="border: 4px solid transparent;
                    border-image: linear-gradient(to top, {{$promotions[0]->bg_color}}, {{$promotions[0]->text_color}}); border-image-slice: 1;"> 
                    <h4 class="text-center pt-3" style="color:{{$promotions[0]->text_color}}">{{ $promotions[0]->title }}</h4>
                    </a>
                </center>
            </div>
        </div>
    </div>           
    @elseif($promotions->count() > 1)
    <div class="banner-section mt-5 overflow-hidden">
        <div class="banner-section-inner">
            <div class="container">
                <div class="row @if($promotions->count()==2)justify-content-center @endif">
                    @foreach ($promotions as $key=>$promotion)
                    <div class="col-lg-6 col-md-6 col-12" data-aos="fade-right" data-aos-duration="1200">
                        <a class="banner-item position-relative rounded" href="{{ route('promo-items', [app()->getLocale(),$promotion->slug]) }}">
                            <img class="banner-img" src="{{ url('storage').'/'.$promotion->photo }}" class="article-card-img">
                            <div class="content-absolute content-slide">
                                <div class="container height-inherit d-flex align-items-center">
                                    <div class="content-box banner-content p-4">
                                        {{-- <p class="heading_18 mb-3 text-white">{{$promotion->title}}</p>
                                        <h2 class="heading_34 text-white">25% off for <br>sports men</h2> --}}
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
