@if($sub_categories->count()>0)
<div class="featured-collection-section mt-100 home-section overflow-hidden" data-aos="fade-up" data-aos-duration="700">
    <div class="container">
        <div class="section-header">
            <h2 class="section-heading">Shop By Category</h2>
        </div>

        <div class="product-container position-relative">
            <div class="common-slider"  data-slick='{
                "slidesToShow": 7,
                "slidesToScroll": 2,
                "dots": true,
                "arrows": true,
                "responsive": [
                {
                    "breakpoint": 1281,
                    "settings": {
                    "slidesToShow": 5
                    }
                },
                {
                    "breakpoint": 768,
                    "settings": {
                    "slidesToShow": 2
                    }
                }
                ],
                "autoplay": true,
                "autoplaySpeed": 1500
            }'>
                <?php $catView =  \App\Models\Setting::where('type','cat-view')->pluck('value')->first();?>
                @foreach ($sub_categories as $key=>$sub)
                    <div class="col-2" data-aos="fade-up" data-aos-duration="{{$key + 200}}">
                        <div class="product-card">
                            <div class="product-card-img">
                                <a class="hover-switch" href="{{ route('group-in', [app()->getLocale(), $sub->slug]) }}">
                                    <img style="width:140px;height:140px;border:1px solid #00234d;padding:3px; @if($catView=='circle')border-radius:120px; @endif" 
                                    class="primary-img" src="{{ url('storage').'/'.$sub->photo }}" alt="{{ $sub->title }}">
                                </a>
                            </div>
                            <div class="product-card-details text-center">
                                <h3 class="product-card-title"><a href="{{ route('group-in', [app()->getLocale(), $sub->slug]) }}">{{ $sub->title }}</a></h3>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="activate-arrows show-arrows-always article-arrows arrows-white"></div>
        </div>
    </div>
</div>
@endif

<script src="{{asset('/assets/js/main.js')}}"></script>