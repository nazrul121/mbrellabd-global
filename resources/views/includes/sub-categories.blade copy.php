
<section  class="elementor-section elementor-top-section elementor-element elementor-element-451e86f1 elementor-section-full_width elementor-section-height-default elementor-section-height-default"
data-id="451e86f1"  data-element_type="section">
<div class="elementor-container elementor-column-gap-no">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-32e855b1" data-id="32e855b1" data-element_type="column">
        <div class="elementor-widget-wrap elementor-element-populated">
            <div class="elementor-element elementor-element-5c2b587a elementor-widget elementor-widget-cbh_product_categories">
                <div class="elementor-widget-container">
                    <div class="flex items-center justify-between -mt-2 lg:-mt-2.5 mb-4 md:mb-6 xl:mb-8">
                        <h2 class="text-lg md:text-xl lg:text-2xl xl:text-3xl font-bold mb-0 heading_title">Shop By Category</h2>
                    </div>
                    <div class="carousel-container relative">
                        <div class="swiper-container horiz-cat-slider horiz-cat-slider-5c2b587a">
                            <div class="swiper-wrapper">
                                <?php $catView =  \App\Models\Setting::where('type','cat-view')->pluck('value')->first();?>
                                @foreach ($sub_categories as $key=>$sub)
                                <a class="swiper-slide group flex justify-center text-center flex-col" href="{{ url('group-in').'/'.$sub->slug }}">
                                    <div class="chawkbazar-fadeIn-image-js bg-gray-300 relative inline-flex items-center mb-3.5 md:mb-4 lg:mb-5 xl:mb-6 mx-auto @if($catView=='circle')rounded-full @endif">
                                        <img  loading="lazy" width="210" height="210"src="{{ url('storage').'/'.$sub->photo }}" class="opacity-0 transition-opacity duration-500 object-cover @if($catView=='circle')rounded-full @endif"/>
                                        <div class="absolute top left bg-black w-full h-full opacity-0 transition-opacity duration-300 group-hover:opacity-30 @if($catView=='circle')rounded-full @endif"></div>
                                        <div class="absolute top left h-full w-full flex items-center justify-center">
                                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512"
                                                class="text-white text-base sm:text-xl lg:text-2xl xl:text-3xl transform opacity-0 scale-0 transition-all duration-300 ease-in-out group-hover:opacity-100 group-hover:scale-100"
                                                height="1em"  width="1em"  >
                                                <path d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"
                                                ></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <h4 class="capitalize mb-0 overflow-ellipsis overflow-hidden font-semibold text-xs md:text-sm xl:text-lg">{{ $sub->title }}</h4>
                                </a>
                               @endforeach
                            </div>
                            <!-- scrollbar -->
                            <div class="swiper-scrollbar horiz-cat-slider-scrollbar horiz-cat-slider-scrollbar-5c2b587a"></div>
                        </div>
                        <button
                            title="Prev"
                            class="absolute top-2/4 -mt-8 md:-mt-10 z-50 horiz-cat-arrow-prev horiz-cat-arrow-prev-5c2b587a w-8 h-8 lg:w-10 lg:h-10 xl:w-12 xl:h-12 p-0 text-lg text-black flex items-center justify-center rounded-full bg-white shadow-navigation transition duration-250 hover:bg-gray-900 hover:text-white focus:outline-none focus:bg-gray-900 focus:text-white left-0 transform -translate-x-2/4 border-0 hide-on-mobile"
                        >
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em">
                                <path
                                    d="M217.9 256L345 129c9.4-9.4 9.4-24.6 0-33.9-9.4-9.4-24.6-9.3-34 0L167 239c-9.1 9.1-9.3 23.7-.7 33.1L310.9 417c4.7 4.7 10.9 7 17 7s12.3-2.3 17-7c9.4-9.4 9.4-24.6 0-33.9L217.9 256z"
                                ></path>
                            </svg>
                        </button>
                        <button
                            title="Next"
                            class="absolute top-2/4 -mt-8 md:-mt-10 z-50 horiz-cat-arrow-next horiz-cat-arrow-next-5c2b587a w-8 h-8 lg:w-10 lg:h-10 xl:w-12 xl:h-12 p-0 text-lg text-black flex items-center justify-center rounded-full bg-white shadow-navigation transition duration-250 hover:bg-gray-900 hover:text-white focus:outline-none focus:bg-gray-900 focus:text-white right-0 transform translate-x-2/4 border-0 hide-on-mobile"
                        >
                            <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em">
                                <path
                                    d="M294.1 256L167 129c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.3 34 0L345 239c9.1 9.1 9.3 23.7.7 33.1L201.1 417c-4.7 4.7-10.9 7-17 7s-12.3-2.3-17-7c-9.4-9.4-9.4-24.6 0-33.9l127-127.1z"
                                ></path>
                            </svg>
                        </button>
                    </div>
                    <script>
                        jQuery(document).ready(function () {
                            new Swiper(".horiz-cat-slider-5c2b587a", {
                                slidesPerView: 3.5,
                                spaceBetween: 12,
                                loop: false, autoplay: { delay: 1000},
                                scrollbar: {
                                    el: ".horiz-cat-slider-scrollbar-5c2b587a",
                                    draggable: true,
                                },
                                breakpoints: {
                                    768: {
                                        slidesPerView: 5,
                                        spaceBetween: 16,
                                        loop: true,
                                    },
                                    1024: {
                                        slidesPerView: 5,
                                        spaceBetween: 30,
                                        loop: true,
                                    },
                                    1280: {
                                        slidesPerView: 6,
                                        spaceBetween: 30,
                                        loop: true,
                                    },
                                    1440: {
                                        slidesPerView: 7,
                                        spaceBetween: 30,
                                        loop: true,
                                    },
                                    1600: {
                                        slidesPerView: 8,
                                        spaceBetween: 30,
                                        loop: true,
                                    },
                                },
                                navigation: {
                                    prevEl: ".horiz-cat-arrow-prev-5c2b587a",
                                    nextEl: ".horiz-cat-arrow-next-5c2b587a",
                                },
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
