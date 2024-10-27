@php
    $page_types = \DB::table('page_post_types')->select('id','title','slug')->get();
    $policy_types = \DB::table('policy_types')->select('id','title','slug')->get();
    $order_types = \App\Models\Order_status::select('id','title')->orderBy('id')->get();
    $promotion_types = \DB::table('promotion_types')->select('id','title')->get();
@endphp

<nav class="pcoded-navbar">
    <div class="navbar-wrapper">
        <div class="navbar-brand header-logo">
            <a href="{{ url(Auth::user()->user_type->title) }}/dashboard" class="b-brand"> <img alt="" src="{{ url('storage/images/header-logo.png') }}" height="40"/> </a>
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
        </div>
        <div class="navbar-content scroll-div">
            <ul class="nav pcoded-inner-navbar">
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='dashboard') pcoded-trigger @endif">
                    <a href="{{ url(Auth::user()->user_type->title) }}/dashboard" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>

                <li class="nav-item pcoded-menu-caption"> <label>Sales</label></li>
                @if(is_label_has_nay_permissions(1))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='catalog')pcoded-trigger @endif">
                    <a href="javaScript:;" class="nav-link"><span class="pcoded-micon"><i class="feather icon-shopping-cart"></i></span><span class="pcoded-mtext">Product line</span></a>
                    <ul class="pcoded-submenu" style="display:@if(Request::segment(2)=='catalog') block @endif">
                        <li class="nav-item pcoded-hasmenu @if(Request::segment(3)=='category')pcoded-trigger active @endif">
                            <a href="javaScript:;" class="nav-link"><span class="pcoded-mtext">0. Item Setup</span></a>
                            <ul class="pcoded-submenu" style="display:@if(Request::segment(3)=='category') block @endif">
                                @if(check_access('view-main-category'))
                                <li class="@if(Request::segment(4)=='main')active @endif"><a href="{{ route('common.category') }}" >1. Main Groups</a></li>@endif

                                @if(check_access('view-sub-category'))
                                <li class="@if(Request::segment(4)=='sub')active @endif"><a href="{{ route('common.sub-category') }}" >2. Sub Groups</a></li> @endif

                                @if(check_access('view-child-category'))
                                <li class="@if(Request::segment(4)=='child')active @endif"><a href="{{ route('common.child-category') }}" >3. Child Groups</a></li>@endif

                                @if(check_access('group-ordering'))
                                <li class="@if(Request::segment(4)=='ordering')active @endif"><a href="{{ route('common.category-ordering') }}" >4. Group Ordering</a></li>@endif

                                @if(check_access('view-size-chirt'))
                                <li class="@if(Request::segment(4)=='size-chirt')active @endif"><a href="{{ route('common.size-chirt') }}" >5. Size Chirt</a></li>@endif

                                @if(check_access('view-size-chirt-pdf'))
                                <li class="@if(Request::segment(4)=='size-chirt-for-all')active @endif"><a href="{{ route('common.size-chirt-for-all') }}" >6. Size Chirt PDF</a></li>@endif

                                @if(check_access('view-product-variation'))
                                <li class="@if(Request::segment(4)=='variant')active @endif"><a href="{{ route('common.variant') }}" >7. Variations</a></li>@endif
                            </ul>
                        </li>

                        @if(check_access('create-product'))
                        <li class="@if(Request::segment(4)=='create')active @endif"><a href="{{ route('common.product.create') }}" >1. Upload Product</a></li> @endif

                        @if(check_access('view-product-list'))
                        <li class="@if(Request::segment(3)=='product' && Request::segment(4)=='')active @endif"><a href="{{ route('common.product') }}" >2. Product list view</a></li> @endif

                        @if(check_access('view-product-highlight'))
                        <li class="@if(Request::segment(4)=='highlight')active @endif"><a href="{{ route('common.highlight') }}" >3. Highlights</a></li> @endif

                        @if(check_access('view-season'))
                        <li class="@if(Request::segment(3)=='season')active @endif"><a href="{{ route('common.season') }}" >4. Seasons</a></li>@endif
                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(2))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='order') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-bar-chart"></i></span><span class="pcoded-mtext">Orders </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='order')block @endif">
                        @if(check_access('create-order'))
                        <li class="@if(Request::segment(3)=='create') active @endif"><a href="{{ route('common.order.create') }}" >0. Create an order</a></li> @endif

                        @foreach ($order_types as $key=>$type)
                            @if(check_access('view-order'))
                            <li class="@if(Request::segment(3)==$type->id) active @endif"><a href="{{ route('common.orders',$type->id) }}" >{{ $key+1 }}. {{ $type->title }}
                                <span class="pcoded-badge label label-info">{{ $type->orders()->count() }}</span> </a></li> @endif
                        @endforeach

                        @if(check_access('view-order'))
                        <li class="@if(Request::segment(3)=='all-orders')active @endif"><a href="{{ route('common.all-orders') }}" >{{ $order_types->count() +1 }}. All orders</a></li> @endif

                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(14))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='report') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-file-plus"></i></span><span class="pcoded-mtext">Reports </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='report')block @endif">
                        @if(check_access('view-report'))
                            <li class="@if(strpos(Request::segment(3), 'order') !== false)active @endif"><a href="{{ route('common.order-export') }}" >1. Export Orders</a></li> 

                            <li class="@if(Request::segment(3)=='area-wise-order')active @endif"><a href="{{ route('common.area-wize-orders') }}" >2. Area wize orders</a></li>

                            <li class="@if(Request::segment(3)=='addToCart')active @endif"><a href="{{route('common.reprt.add-to-cart')}}" >3. Add to Cart</a></li>
                            
                            <li class="@if(strpos(Request::segment(3),'customer') !== false)active @endif"><a href="{{route('common.customer-order')}}" >4. Customer reports</a></li>
                            <li class="@if(Request::segment(3)=='order-durations')active @endif"><a href="{{route('common.order-durations')}}" >5. Order Durations</a></li>
                        
                            <li class="@if(Request::segment(3)=='last-week-orders')active @endif"><a href="{{route('common.last-week-orders')}}" >6. Order Progress Report </a></li>

                            <li class="@if(Request::segment(3)=='company-report')active @endif"><a href="{{route('common.courier.company.report',0)}}" >7. Courier order Report </a></li>

                            <li class="@if(Request::segment(3)=='single-product-report')active @endif"><a href="{{route('common.single-product-report')}}" >8. Single product Report </a></li>
                            
                            <li class="@if(Request::segment(3)=='sslcommerz')active @endif"><a href="{{route('common.sslcommerz-orders')}}" >9. Online payment Report </a></li>
                        @endif
                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(3))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='courier') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-truck"></i></span><span class="pcoded-mtext">Courier<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='courier')block @endif">
                        @if(check_access('ready-order-for-shipment'))
                        <li class="@if(Request::segment(3)=='ready-to-ship') active @endif"><a href="{{ route('common.ready-to-ship') }}" >1. Read to Ship</a></li> @endif

                        @if(check_access('view-courier-company'))
                        <li class="@if(Request::segment(3)=='companies') active @endif"><a href="{{ route('common.couriers') }}" >2. Courier Companies</a></li>@endif

                        @if(check_access('view-courier-representative'))
                        <li class="@if(Request::segment(3)=='company-man') active @endif"><a href="{{ route('common.couriers.man') }}" >3. Company Mans</a></li>@endif

                        @if(check_access('view-courier-zone'))
                        <li class="@if(Request::segment(3)=='zone') active @endif"><a href="{{ route('common.courier.zone') }}" >4. Courier Zones </a></li>@endif

                        {{-- @if(check_access('view-dhl')) --}}
                        <li class="@if(Request::segment(3)=='dhl-setup') active @endif"><a href="{{ route('common.dhl-setup') }}" >5. DHL Setup </a></li>
                        {{-- @endif --}}
                    </ul>
                </li> @endif


                @if(is_label_has_nay_permissions(4))
                <li class="nav-item pcoded-hasmenu @if(Request::segment(2)=='ad')pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fab fa-adversal"></i></span><span class="pcoded-mtext">Promotion Setup </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='ad') block @endif">
                        @if(check_access('view-coupons'))
                        <li class="@if(Request::segment(3)=='coupon')active @endif"><a href="{{ route('common.coupon') }}">Coupons</a></li>@endif

                        @if(check_access('view-invoice-discount'))
                        <li class="@if(Request::segment(3)=='invoice-discount')active @endif"><a href="{{ route('common.invoice-discount') }}" >Invoice Discount</a></li>@endif

                        @if(check_access('view-promotion'))
                        <li class="pcoded-hasmenu @if(Request::segment(3)=='promotion')active @endif"><a href="#!" class="">Promotion<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:@if(Request::segment(3)=='promotion') block @endif">
                                @foreach ($promotion_types as $key=>$type)
                                <li class="@if($type->id==Request::segment(4))active @endif"><a href="{{ route('common.promotion', $type->id) }}" >{{ $key+1 }}. {{ $type->title }}</a></li>
                                @endforeach
                            </ul>
                        </li> @endif
                        @if(check_access('view-campaign'))
                        <li class="@if(Request::segment(3)=='campaign')active @endif"><a href="{{ route('common.campaign') }}">Campaigns</a></li>@endif

                        @if(check_access('view-banner'))
                        <li class="@if(Request::segment(3)=='banner')active @endif"><a href="{{ route('common.banner') }}">Banners</a></li>@endif
                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(5))
                <li class="nav-item pcoded-hasmenu @if(Request::segment(2)=='page-post') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-paperclip"></i></span><span class="pcoded-mtext">Page Post</span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='page-post')block @endif">
                        @if(check_access('view-page-video'))
                        <li class="@if(Request::segment(3)=='videos')active @endif"><a href="{{ route('common.videos') }}">1. Videos</a></li>@endif

                        @if(check_access('view-home-slider'))
                        <li class="@if(Request::segment(3)=='slider')active @endif"><a href="{{ route('common.slider') }}">2. Sliders</a></li>@endif

                        @if(check_access('view-page-post'))
                        <li class="pcoded-hasmenu @if(Request::segment(3)=='page')active @endif"><a href="javaScript:;">3. Page<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:@if(Request::segment(3)=='page') block @endif">
                                @foreach ($page_types as $key=>$type)
                                <li class="@if(Request::segment(4)==$type->id || Request::segment(5)==$type->id)active @endif"><a href="{{ route('common.page-post', $type->id) }}">{{ $key+1 }}. {{ $type->title }}</a></li>
                                @endforeach
                            </ul>
                        </li> @endif

                        @if(check_access('view-policy'))
                        <li class="pcoded-hasmenu @if(Request::segment(3)=='policy')active @endif"><a href="#!" class="">4. Policies</a>
                            <ul class="pcoded-submenu" style="display:@if(Request::segment(3)=='policy') block @endif">
                                @foreach ($policy_types as $key=>$type)
                                <li class="@if(Request::segment(4)==$type->slug)active @endif"><a href="{{ route('common.policy', $type->slug) }}">{{ $key+1 }}. {{ $type->title }}</a></li>
                                @endforeach
                            </ul>
                        </li> @endif
                        @if(check_access('view-blog'))
                        <li class="pcoded-hasmenu @if(Request::segment(3)=='blog')active @endif"><a href="#!" class="">5. Blog<sub>s</sub></a>
                            <ul class="pcoded-submenu" style="display:@if(Request::segment(3)=='blog') block @endif">
                                @if(check_access('create-blog'))
                                <li class="@if(Request::segment(4)=='create')active @endif"><a href="{{ route('common.blog.create') }}" >1. Create blog post</a></li> @endif
                                <li class="@if(Request::segment(4)=='')active @endif"><a href="{{ route('common.blogs') }}" >2. Blogs</a></li>
                                <li class="@if(Request::segment(4)=='category')active @endif"><a href="{{ route('common.blog-category') }}" >3. Blog Categories</a></li>
                            </ul>
                        </li> @endif

                        @if(check_access('view-faq'))
                        <li class="@if(Request::segment(3)=='faq') active @endif"><a href="{{ route('common.faq') }}" >6. FAQs</a></li>@endif
                    </ul>
                </li> @endif

                <li class="nav-item pcoded-menu-caption"> <label>Quick Area</label></li>
                @if(check_access('view-quick-service'))
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='quick-service') pcoded-trigger @endif">
                    <a href="{{ route('common.quick-service') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-tags"></i></span><span class="pcoded-mtext">Quick services</span></a>
                </li> @endif

                @if(check_access('view-testimonial'))
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='testimonial') pcoded-trigger @endif">
                    <a href="{{ route('common.testimonial') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-comments"></i></span><span class="pcoded-mtext">Testimonials</span></a>
                </li>@endif

                @if(check_access('view-career'))
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='career') pcoded-trigger @endif">
                    <a href="{{ route('common.career') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-graduation-cap"></i></span><span class="pcoded-mtext">Career</span></a>
                </li>@endif

                <li class="dashboard Default Ecommerce CRM Analytics Crypto Project">
                    <a class="nav-item" href="{{route('common.meta')}}">
                        <span class="pcoded-micon"> <i class="fa fa-code menu-icon"></i> </span>
                        <span class="menu-title">Meta info (static)</span>
                    </a>
                </li>

                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item ">
                    <a href="{{ route('common.sitemap') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-map"></i></span><span class="pcoded-mtext">Site Map</span></a>
                </li>


                <li class="nav-item pcoded-menu-caption"> <label>Management</label></li>
                @if(is_label_has_nay_permissions(8))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='user') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">User<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='user')block @endif">
                        @if(check_access('view-customer'))
                        <li class="@if(Request::segment(3)=='customer') active @endif"><a href="{{ route('common.customer') }}" >1. Customers</a></li> @endif

                        <li class="pcoded-hasmenu @if(Request::segment(3)=='employee')active @endif"><a href="#!" class="">2. Employees</a>
                            <ul class="pcoded-submenu" style="display: @if(Request::segment(3)=='employee') block; @else none @endif">
                                @if(check_access('view-staff'))
                                <li class="@if(Request::segment(3)=='employee' && Request::segment(4)=='') active @endif"><a href="{{ route('common.employee') }}" >A. Employee data</a></li> @endif

                                @if(check_access('view-staff-dept'))
                                <li class="@if(Request::segment(4)=='category') active @endif"><a href="{{ route('common.employee-category') }}" >B. Employee Departments</a></li> @endif
                            </ul>
                        </li>
                        @if(check_access('view-supplier'))
                        <li class="@if(Request::segment(3)=='supplier') active @endif"><a href="{{ route('common.supplier') }}" >3. Supliers</a></li>@endif

                        @if (Auth::user()->user_type_id==1 || Auth::user()->user_type_id==2)
                            <li class="@if(Request::segment(3)=='admin') active @endif"><a href="{{ route('common.admin') }}" >4. Administrators</a></li>
                            <li class="@if(Request::segment(3)=='user-types') active @endif"><a href="{{ route('common.user-types') }}" >5. Access Labels</a></li>
                        @else
                            @if(check_access('view-access-label'))
                            <li class="@if(Request::segment(3)=='user-types') active @endif"><a href="{{ route('common.user-types') }}" >4. Access Labels</a></li>@endif
                        @endif
                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(9))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='settings') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Setting<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='settings')block @endif">
                        @if(check_access('system-settings'))
                        <li class="@if(Request::segment(3)=='system-settings') active @endif"><a href="{{ route('common.system-settings') }}" >1. System Settings</a></li>@endif

                        @if(check_access('quick-settings'))
                        <li class="@if(Request::segment(3)=='quick-setting') active @endif"><a href="{{ route('common.quick-setting') }}" >2. Quick Settings</a></li> @endif

                        @if(check_access('view-social-media'))
                        <li class="@if(Request::segment(3)=='social-settings') active @endif"><a href="{{ route('common.social-settings') }}" >3. Social Media</a></li>@endif

                        @if(check_access('view-currency'))
                        <li class="@if(Request::segment(3)=='currency' || Request::segment(3)=='dollar') active @endif"><a href="{{ route('common.currency') }}" >4. Country</a></li>@endif

                        @if(check_access('order-setup'))
                        <li class="@if(Request::segment(3)=='order-status') active @endif"><a href="{{ route('common.order-status') }}" >5. Order Setup</a></li> @endif

                        @if(check_access('mail-config'))
                        <li class=""><a href="javaScript:;" >Mail Configuration</a></li> @endif
                    </ul>
                </li> @endif

                @if(is_label_has_nay_permissions(10))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='payment') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="fas fa-dollar-sign"></i></span><span class="pcoded-mtext">Payments<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='payment')block @endif">
                        @if(check_access('view-payment-method'))
                        <li class="@if(Request::segment(3)=='payment-method') active @endif"><a href="{{ route('common.payment-gateway') }}" >1. Payment Methods</a></li>@endif

                        @if(check_access('view-payment-type'))
                        <li class="@if(Request::segment(3)=='payment-type') active @endif"><a href="{{ route('common.payment-type') }}" >2. Payment Types</a></li> @endif
                    </ul>
                </li> @endif


                @if(is_label_has_nay_permissions(11))
                <li data-username="Vertical Horizontal Box Layout RTL fixed static Collapse menu color icon dark" class="nav-item pcoded-hasmenu @if(Request::segment(2)=='area') pcoded-trigger @endif">
                    <a href="#!" class="nav-link"><span class="pcoded-micon"><i class="feather icon-map-pin"></i></span><span class="pcoded-mtext">Area & Zone<sub>s</sub> </span></span></a>
                    <ul class="pcoded-submenu" style="display: @if(Request::segment(2)=='area')block @endif">
                        @if(check_access('view-area'))
                        <li class="@if(Request::segment(3)=='' && Request::segment(2)=='area') active @endif"><a href="{{ route('common.area') }}?country=2" >1. Area Setup</a></li>@endif

                        @if(check_access('view-zone'))
                        <li class="@if(Request::segment(3)=='zone') active @endif"><a href="{{ route('common.area.zone') }}" >2. Zone Setup</a></li>@endif
                    </ul>
                </li>@endif

                @if(Auth::user()->user_type_id==1 || Auth::user()->user_type_id==2)
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='permissions') pcoded-trigger @endif">
                    <a href="{{ route('common.permissions') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-crop"></i></span><span class="pcoded-mtext">Permissions</span></a>
                </li>@endif

                @if(check_access('view-outlet'))
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='showroom') pcoded-trigger @endif">
                    <a href="{{ route('common.showroom') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-store"></i></span><span class="pcoded-mtext">Outlets</span></a>
                </li>@endif

                @if(check_access('database-backup'))
                <li data-username="dashboard Default Ecommerce CRM Analytics Crypto Project" class="nav-item @if(Request::segment(2)=='backup') pcoded-trigger @endif">
                    <a href="{{ route('common.get-backup') }}" class="nav-link"><span class="pcoded-micon"><i class="fa fa-database"></i></span><span class="pcoded-mtext">Database BackUp</span></a>
                </li> @endif

            </ul>
        </div>
    </div>
</nav>
