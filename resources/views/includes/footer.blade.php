<footer class="overflow-hidden">
    <div class="footer-top pb-0" style="background: #0f3c3f;">
        <div class="container-fluid " >
            <div class="footer-widget-wrapper">
                <div class="row justify-content-between">

                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Social Media</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">  <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                @foreach ($socials as $media)
                                    <li class="footer-menu-item"><a class="text-white" target="_blank" href="{{ $media->media_link }}"><i class="{{ $media->media_icon }}"></i> {{ $media->media_name }}</a></li>
                                @endforeach
                                <li>  <br>
                                    <div class="fb-like" data-href="https://www.facebook.com/MbrellaByMondol" data-width="" data-layout="button_count" data-action="like" data-size="small" data-share="true"></div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Categories</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">  <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                @foreach ($category as $group)
                                    <li class="footer-menu-item"><a class="text-white" href="{{ route('group', [app()->getLocale(),$group->slug]) }}">{{ $group->title }} ({{$group->products()->count()}})</a></li>
                                @endforeach
                                <li class="footer-menu-item"><a class="text-white" href="{{ route('size-guide', app()->getLocale()) }}">Product size guide</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>About Us</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <li class="footer-menu-item"><a class="text-white" href="{{ route('faqs',[app()->getLocale()]) }}">FAQs</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="{{ route('showrooms', app()->getLocale()) }}">Store Locations</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="{{ route('career',app()->getLocale()) }}">Career</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="{{ route('blog',app()->getLocale()) }}">Blog / news feed</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="{{ url('register') }}">Register</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Policies</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                @foreach ($policies as $type)
                                <li class="footer-menu-item"><a class="text-white" href="{{ url('about/policy').'/'.$type->slug }}">{{ $type->title }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-5 col-md-6 col-12 footer-widget">
                        <div class="footer-widget-inner">
                            <h4 class="footer-heading d-flex align-items-center justify-content-between text-white">
                                <span>Contacts</span>
                                <span class="d-md-none">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg"
                                        width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#00234D"
                                        stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="6 9 12 15 18 9"></polyline>
                                    </svg>
                                </span>
                            </h4>
                            <ul class="footer-menu list-unstyled mb-0 d-md-block">
                                <li class="footer-menu-item"><a class="text-white"href="javaScript:;">{{ request()->get('system_title') }}</a></li>
                                <li class="footer-menu-item"><a class="text-white"href="mailto:{{ request()->get('system_email') }}">{{ request()->get('system_email') }}</a></li>
                                <li class="footer-menu-item"><a class="text-white" href="tel:{{ request()->get('system_helpline') }}">Call us: {{ request()->get('system_helpline') }}</a></li>
                                <li class="footer-menu-item"><a class="text-white" target="_blank" href="https://www.google.com/maps/search/{{ str_replace('#','',str_replace(' ','+',request()->get('office_address'))) }}/@23.8611459,90.3857328,13.75z">{{ request()->get('office_address') }}</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- <div class="row justify-content-between text-center">
                    <div class="col-xl-4 col-lg-4 col-md-4 col-12 text-md-end mt-md-4">
                        <strong class="label mb-1 d-block text-white">Find us on Social media:</strong>
                    </div>
                    <div class="col-xl-8 col-lg-8 col-md-8 col-12">
                        <div class="share-area mt-4 d-flex align-items-center">
                            
                            <ul class="list-unstyled share-list d-flex row col-12">
                                @foreach ($socials as $media)
                                    <li class="col-6 col-md-3"><a target="_blank" href="{{ $media->media_link }}"><i class="{{ $media->media_icon }}"></i> {{ $media->media_name }}</a></li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div> --}}
            </div>
        </div>
        <div class="row pb-4">
            <a href="{{ url('storage/images/portPost_footer.png') }}" target="_blank">
                <img class="d-lg-block d-none" src="{{ url('storage/images/portPost_footer.png') }}" style="width:100%">
                <img class="d-none d-sm-block" src="{{ url('storage/images/portPos.webp') }}" style="width:100%">
            </a>
        </div>
    </div>
    <div class="footer-bottom bg-brand">
        <div class="container mb-1">
            <div class="footer-bottom-inner d-flex flex-wrap justify-content-md-between justify-content-center align-items-center">
                <ul class="footer-bottom-menu list-unstyled d-flex flex-wrap align-items-center mb-0">
                    @foreach ($pages as $type)
                    <li class="footer-menu-item"><a href="{{ url('page').'/'.$type->slug }}">{{ $type->title }}</a></li>
                    @endforeach
                    <li class="footer-menu-item"><a href="/truck">Track order</a></li>
                    
                </ul>
                <p class="copyright footer-text"> &copy; <span class="current-year"></span> Mbrella ltd. By <b>IT-Station @ Mbrella</b></p>
            </div>
        </div>
    </div>
</footer>
