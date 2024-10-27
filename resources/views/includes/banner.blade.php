@php
    $banner = \DB::table('banners')->where(['position'=>'home','status'=>'1'])->inRandomOrder()->first();
@endphp

@if($banner !=null)
<section class="elementor-section elementor-top-section elementor-element elementor-element-44ef9f45 elementor-section-full_width elementor-section-height-default elementor-section-height-default">
    <div class="elementor-container elementor-column-gap-no">
    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-6866152b">
            <div class="elementor-widget-wrap elementor-element-populated">
                <div class="elementor-element elementor-element-a560d24 elementor-widget elementor-widget-cbh_banner_content_block">
                    <div class="elementor-widget-container">
                        <a class="flex items-center justify-center group relative overflow-hidden banner_content_block" href="@if($banner->link==null) javaScript:; @else {{ $banner->link }} @endif" target="_blank">
                            <img src="{{ url('storage/'.$banner->photo) }}" alt="Banner content block" class="transition-opacity duration-500 rounded-md absolute top-0 left-0" style="width:100%">
                            <div class="relative z-10 banner_contents_wrap">
                                <!--<h2 class="banner_content_block_title mb-0 font-regular">{{ $banner->title }}</h2>-->
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endif
