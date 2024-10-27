@if ($sliders->count()>0)
    <div class="slideshow-section position-relative">
        <div class="slideshow-section position-relative">
            <div class="slideshow-active activate-slider" data-slick='{
                "slidesToShow": 1, 
                "slidesToScroll": 1, 
                "dots": true,
                "arrows": true,
                "responsive": [
                    {
                    "breakpoint": 768,
                    "settings": {
                        "arrows": false
                    }
                    }
                ],
                "autoplay": true,
                "autoplaySpeed": 3000
                }'>
                @foreach ($sliders as $key=>$slider)                                   
                    <div class="slide-item slide-item-bag position-relative">
                        <a href="{{ $slider->link }}">
                            <img class="slide-img d-none d-md-block" src="{{url('storage/'.$slider->photo)}}" alt="slide-1">
                            <img class="slide-img d-md-none" src="{{url('storage/'.$slider->photo)}}" alt="slide-1">
                            <!---
                            <div class="content-absolute content-slide">
                                <div class="container height-inherit d-flex align-items-center justify-content-end">
                                    <div class="content-box slide-content slide-content-1 py-4">
                                        <h2 class="slide-heading heading_72 animate__animated animate__fadeInUp"
                                            data-animation="animate__animated animate__fadeInUp">
                                            Discover The Best Furniture
                                        </h2>
                                        <p class="slide-subheading heading_24 animate__animated animate__fadeInUp"
                                            data-animation="animate__animated animate__fadeInUp">
                                            Look for your inspiration here
                                        </p>
                                        <a class="btn-primary slide-btn animate__animated animate__fadeInUp"
                                            href="collection-left-sidebar.html"
                                            data-animation="animate__animated animate__fadeInUp">SHOP
                                            NOW</a>
                                    </div>
                                </div>
                            </div>-->
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="activate-arrows"></div>
            <div class="activate-dots dot-tools"></div>
        </div>
    </div>
@endif
