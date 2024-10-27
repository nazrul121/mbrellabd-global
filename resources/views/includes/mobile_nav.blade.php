<?php $currencies = \DB::table('countries')->where('status','1')->get();?>


<div class="offcanvas offcanvas-start d-flex d-lg-none" tabindex="-1" id="drawer-menu" style="background-color:rgb(0 0 0 / 64%)">
    <div class="offcanvas-wrapper">
        <div class="offcanvas-header border-btm-black">
            <h5 class="drawer-heading">Menu</h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"
                aria-label="Close"></button>
        </div>
        <div class="offcanvas-body p-0 d-flex flex-column justify-content-between">
            <nav class="site-navigation">
                <ul class="main-menu list-unstyled" style="overflow-y:scroll;height:100vh">
                    <li class="menu-list-item nav-item has-dropdown active">
                        <div class="mega-menu-header"> <a class="nav-link active" href="{{route('home')}}"> Home </a></div>
                    </li>
                    @foreach ($category as $cat)
                        <li class="menu-list-item nav-item has-megamenu">
                            <div class="mega-menu-header">
                                <a class="nav-link" href="{{ route('group',[app()->getLocale(),$cat->slug]) }}">{{$cat->title}}</a>
                                <span class="open-submenu text-white">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="submenu-transform submenu-transform-desktop">
                                <div class="container">
                                    <div class="offcanvas-header border-btm-black">
                                        <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                            <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"
                                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                <polyline points="15 18 9 12 15 6"></polyline>
                                            </svg>
                                            <span class="menu-back-text">{{$cat->title}}</span>
                                        </h5>
                                    </div>
                                    <ul class="submenu megamenu-container list-unstyled" style="overflow-y:auto;height:90vh;">
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="{{ route('group',[app()->getLocale(),$cat->slug]) }}">All of <b>{{$cat->title}}</b></a>
                                            </div>
                                        </li>
                                        @foreach ($cat->inner_groups()->where('status','1')->orderBy('sort_by')->get() as $key=>$sub)
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="{{ route('group-in',[app()->getLocale(),$sub->slug]) }}">{{ $sub->title }}</a>
                                                <span class="open-submenu">
                                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"  stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="submenu-transform">
                                                <div class="offcanvas-header border-btm-black">
                                                    <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                                        <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" 
                                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"  stroke-linecap="round" stroke-linejoin="round" >
                                                            <polyline points="15 18 9 12 15 6"></polyline>
                                                        </svg>
                                                        <span class="menu-back-text">{{ $sub->title }}</span>
                                                    </h5>
                                                </div>
                                                <ul class="megamenu list-unstyled megamenu-container">
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="{{ route('group-in',[app()->getLocale(),$sub->slug]) }}">All of <b>{{ $sub->title }}</b></a>
                                                    </li>
                                                    @foreach ($sub->child_groups()->where('status','1')->orderBy('sort_by')->get() as $child)
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="{{ route('child-in',[app()->getLocale(), $child->slug]) }}">{{ $child->title }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach

                    <li></li>

                    @foreach ($seasons as $season)
                        <?php $groups = \App\Models\Group_season::where(['season_id'=>$season->id, 'status'=>'1'])->get(); ?>
                        <li class="menu-list-item nav-item has-megamenu">
                            <div class="mega-menu-header">
                                <a class="nav-link" href="{{ route('season-products',[app()->getLocale(),$season->slug])}}">  {{$season->title}}  </a>
                                <span class="open-submenu text-white">
                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24"
                                        height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="9 18 15 12 9 6"></polyline>
                                    </svg>
                                </span>
                            </div>
                            <div class="submenu-transform submenu-transform-desktop">
                                <div class="container">
                                    <div class="offcanvas-header border-btm-black">
                                        <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                            <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="15 18 9 12 15 6"></polyline>
                                            </svg> <span class="menu-back-text">{{$season->title}}</span>
                                        </h5>
                                    </div>
                                    <ul class="submenu megamenu-container list-unstyled" style="overflow-y:auto;height:90vh;">
                                        @foreach ($groups as $key=>$item)
                                        <?php $innerGroups = \App\Models\Inner_group_season::where(['group_id'=>$item->group->id,'season_id'=>$season->id,'status'=>'1'])->get();?>
                                        <li class="menu-list-item nav-item-sub">
                                            <div class="mega-menu-header">
                                                <a class="nav-link-sub nav-text-sub megamenu-heading" href="{{ route('season-group', [app()->getLocale(), $season->slug,$item->group->slug]) }}">
                                                    {{$item->group->title}}
                                                </a>
                                                <span class="open-submenu">
                                                    <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" >
                                                        <polyline points="9 18 15 12 9 6"></polyline>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div class="submenu-transform">
                                                <div class="offcanvas-header border-btm-black">
                                                    <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                                        <svg class="icon icon-menu-back"xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"stroke="currentColor" stroke-width="2" >
                                                            <polyline points="15 18 9 12 15 6"></polyline>
                                                        </svg>
                                                        <span class="menu-back-text">{{$item->group->title}}</span>
                                                    </h5>
                                                </div>
                                                <ul class="megamenu list-unstyled megamenu-container">
                                                    @foreach ($innerGroups as $key=>$sub)
                                                    <li class="menu-list-item nav-item-sub">
                                                        <a class="nav-link-sub nav-text-sub" href="{{ route('season-group-in',[app()->getLocale(), $season->slug, $sub->inner_group->slug]) }}">{{ $sub->inner_group->title }}</a>
                                                        <ul class="megamenu list-unstyled megamenu-container">
                                                            <?php $childGroups = \App\Models\Child_group_season::where(['inner_group_id'=>$sub->inner_group->id,'season_id'=>$season->id, 'status'=>'1'])->select('inner_group_id','child_group_id')->get();?>
                                                            @foreach ($childGroups as $child)
                                                            <li class="menu-list-item nav-item-sub">
                                                                <a class="nav-link-sub nav-text-sub" href="{{ route('season-child-in',[app()->getLocale(), $season->slug, $child->child_group->slug]) }}">{{ $child->child_group->title }}</a>
                                                            </li>
                                                            @endforeach
                                                        </ul>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </li>
                                        @endforeach
                                        <li></li>
                                    </ul>
                                </div>
                            </div>
                        </li>
                    @endforeach
                    
                    <li></li>

                    @if($promotions->count() > 0)

                    <li class="menu-list-item nav-item has-dropdown">
                        <div class="mega-menu-header">
                            <a class="nav-link active" href="{{ route('promotions',app()->getLocale())}}">SALE </a>
                            <span class="open-submenu text-white">
                                <svg class="icon icon-dropdown" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"> <polyline points="9 18 15 12 9 6"></polyline>
                                </svg>
                            </span>
                        </div>
                        <div class="submenu-transform submenu-transform-desktop">
                            <div class="offcanvas-header border-btm-black">
                                <h5 class="drawer-heading btn-menu-back d-flex align-items-center">
                                    <svg class="icon icon-menu-back" xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"  stroke-linejoin="round"> <polyline points="15 18 9 12 15 6"></polyline>
                                    </svg>  <span class="menu-back-text"> SALE</span>
                                </h5>
                            </div>
                            <ul class="submenu list-unstyled">
                                @foreach ($promotions as $promo)
                                <li class="menu-list-item nav-item-sub">
                                    <a class="nav-link-sub nav-text-sub" href="{{ route('promo-items',[app()->getLocale(), $promo->slug]) }}">{{ $promo->title }}</a>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </li>
                    @endif

                    @foreach ($pages as $type)
                    <li class="menu-list-item nav-item">
                        <a class="nav-link" href="{{ url('page').'/'.$type->slug }}">{{ $type->title }}</a>
                    </li>
                    @endforeach
                    <li></li>
                    <li class="menu-list-item nav-item"> <a class="nav-link" href="/blog">Blog</a> </li>

                    <li class="menu-list-item nav-item">
                        <a class="announcement-text text-white" href="tel:{{request()->get('system_phone')}}">
                            <span class="fa fa-phone-alt"></span>
                            Call: {{request()->get('system_phone')}}
                        </a>
                    </li>
                    
                    <li class="menu-list-item nav-item">
                        <a class="header-action-item header-wishlist text-white" href="/wishlist">
                            <i class="fas fa-heart"></i>
                            <span>My wishlist</span>
                        </a>
                    </li>

                    <li class="menu-list-item nav-item ">
                        <button type="button" class="currency-btn btn-reset" data-bs-toggle="dropdown" aria-expanded="false">
                            <img class="flag" src="{{url(Session::get('user_currency')->flag)}}" alt="{{Session::get('user_currency')->short_name}}">
                            <span class="text-uppercas text-white">{{Session::get('user_currency')->short_name}}</span>
                            <span class="utilty-icon-wrapper">
                                <svg class="icon icon-dropdown bg-white" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="1" stroke-linecap="round" stroke-linejoin="round">
                                    <polyline points="6 9 12 15 18 9"></polyline>
                                </svg>
                            </span>
                        </button>
                      
                        <ul class="currency-list dropdown-menu dropdown-menu-end px-2">
                            @foreach (get_currency() as $item)
                                <li class="currency-list-item ">
                                    <a class="currency-list-option" href="{{ route('change-currency', [$item->short_name]) }}" data-value="{{ $item->short_name }}">
                                        <img class="flag" src="{{ $item->flag }}" alt="{{ $item->short_name }}">
                                        <span class="text-uppercas">{{ $item->short_name }}</span>
                                    </a>
                                </li>
                            @endforeach   
                        </ul>
                    </li>
                    @if(!auth()->check())
                        <li class="menu-list-item nav-item">
                            <a class="announcement-login announcement-text  text-white" href="/login">
                                <i class="fas fa-sign-in-alt"></i> <span> Login</span>
                            </a>
                        </li>
                        @else 
                        <li class="menu-list-item nav-item">
                            <a class="announcement-login announcement-text text-white" href="/dashboard">
                                <i class="fas fa-tachometer-alt"></i> <span> My Panel</span>
                            </a>
                        </li>
                        <li class="menu-list-item nav-item">
                            <a href="#" onclick="$('#logout-formH').submit();" class="announcement-login announcement-text text-danger"><i class="fas fa-sign-out-alt text-danger"></i> Logout</a>
                            <form id="logout-formH" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf </form>
                        </li>
                     @endif

                </ul>
            </nav>
        </div>
    </div>
</div>