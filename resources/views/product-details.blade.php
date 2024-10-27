@extends('layouts.app')

@section('title',$product->title.' | '.request()->get('system_title'))

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($meta_data as $item)
        <meta name="{{$item->meta_type}}" content="{{$item->meta_content}}" />
        <meta property="og:{{$item->meta_type}}" content="{{$item->meta_content}}" />
    @endforeach  
    
    <meta property="og:image" content="{{ $product->feature_photo  }}">
  
@endpush


@section('content')

<?php $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();

    $variation_ids = $product->product_variation_options()->select('variation_id')->distinct('variation_id')->get();

    // foreach ( $product->product_variation_options()->get() as $key => $value) {
    //     echo $value->variation.'<br/>';
    // }

    $old = old_price($product->id, $product->sale_price);
    $new = product_price($product->id, $product->sale_price);
  
    $percent = ( ($old - $new) / $old) * 100;
    if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
    else $formatNumber = 3; 

    $colorPhotos = \DB::table('variation_option_photos')->where('product_id',$product->id)->select('thumbs','photo')->get();

    $cats = \App\Models\Group_product::where('product_id',$product->id)->select('group_id')->get();
    $sub_cats = \App\Models\Inner_group_product::where('product_id',$product->id)->select('inner_group_id')->get();
    $child_cats = \App\Models\Child_group_product::where('product_id',$product->id)->select('child_group_id')->get();
                       
?>
    <div class="breadcrumb">
        <div class="container">
            <ul class="list-unstyled d-flex align-items-center m-0">
                <li><a href="{{route('home')}}">Home</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"  xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4">
                            <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" />
                        </g>
                    </svg>
                </li>
                @foreach ($cats as $cat)
                    <li><a href="{{ route('group', [app()->getLocale(),$cat->group->slug]) }}">{{ $cat->group->title }}</a></li>
                @endforeach
                {{-- <li>Bag</li> --}}
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4">
                            <path
                                d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"
                                fill="#000" />
                        </g>
                    </svg>
                </li>
                <li>{{$product->title}}</li>
            </ul>
        </div>
    </div>

    <div class="product-page mt-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-12">
                    <div class="product-gallery product-gallery-vertical d-flex">
                        <div class="product-img-large">
                            <div class="img-large-slider">
                                <div class="img-large-wrapper">
                                    <img class="xzoom5" xoriginal="{{ $product->feature_photo }}" src="{{ $product->feature_photo }}">
                                </div>
                            </div>
                        </div>

                        <div class="product-img-thumb">
                            <div class="img-thumb-slider common-slider" data-vertical-slider="true" data-slick='{
                                "slidesToShow":6, 
                                "slidesToScroll": 1,
                                "dots": false,
                                "arrows": true,
                                "infinite": false,
                                "speed": 300,
                                "cssEase": "ease",
                                "focusOnSelect": true,
                                "swipeToSlide": true
                            }'>
                                <div class="thumb0">
                                    <div class="img-thumb-wrapper">
                                        <img src="{{ $product->feature_photo }}" class="thumbImg thumb{{$product->id}}">
                                    </div>
                                </div>
            
                                @foreach($product->product_photos()->get() as $key=>$ph)
                                <div class="thumb{{$key+1}}">
                                    <div class="img-thumb-wrapper">
                                        <img src="{{ $ph->photo }}" class="thumbImg thumb{{$ph->product_id}}" data-key="{{$key+1}}">
                                    </div>
                                </div>
                                @endforeach
                                @php
                                    $productPHotos = $product->product_photos()->count();
                                @endphp
                                @foreach ($colorPhotos as $key=>$cp)
                                <div class="thumb{{$productPHotos+ $key+1}}">
                                    <div class="img-thumb-wrapper">
                                        <img src="{{ $cp->photo }}" class="thumbImg" data-key="{{$productPHotos+ $key+1}}">
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="activate-arrows show-arrows-always arrows-white d-none d-lg-flex justify-content-between mt-3"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-12">
                    <h2 class="container mb-3">{{$product->title}}</h2>
                    
                    <div class="product-details ps-lg-4">
                       
                        <div class="product-price-wrapper mb-4">
                            <span class="product-price regular-price">{{ Session::get('user_currency')->currencySymbol }} {{ number_format(product_price($product->id, $product->sale_price),$formatNumber) }}</span>
                            @if (product_price($product->id, $product->sale_price) < $product->sale_price)
                                @if ($new < $old )
                                    <del class="product-price compare-price ms-2">{{ Session::get('user_currency')->currencySymbol }} {{ number_format($old,$formatNumber) }}</del> @endif

                                @if($percent >0) <b style="text-decoration:line-through!important;"> {{round($percent, 2)}}%off </b> @endif
                            @endif
                            <span class="stockResult"> </span>
                        </div>

                        <div class="product-sku product-meta mb-1">
                            <strong class="label">SKU:</strong> {{$product->sku}} <br>
                            <strong class="label">Product Code:</strong> {{$product->design_code}}
                        </div> <br>

                        <div class="product-variant-wrapper">
                            @foreach ($variation_ids as $key=>$pvo)
        
                                <?php $options = $product->product_variation_options()->where(['variation_id'=>$pvo->variation_id,'status'=>'1'])->select('variation_option_id')->get(); ?>
                                <div class="product-variant product-variant-other">
                                    <select name="variants" id="{{ $pvo->variation_id }}" style="display:none"> <option value=""></option>
                                        @foreach ($options as $key2=> $option) <option class="option{{ $option->variation_option_id }}"  @if($key==0 && $key2==0)selected @endif
                                            value="{{ $option->variation_option_id }}">{{ $option->variation_option->title }}</option>
                                        @endforeach
                                    </select>

                                    <p class="text-dark"><b>{{$pvo->variation->title}}</b>: <span class="showV Vname{{ $pvo->variation_id }}">
                                        @if($key==0){{ $options[0]->variation_option->title }} @endif
                                    </span></p> 
                                    
                                    <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                                        
                                        @foreach ($options as $key2=> $option)
                                            <?php $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$product->id,'variation_option_id'=>$pvo->variation_option_id])->pluck('thumbs')->first(); ?>
                                           
                                            @if(strpos(strtolower($pvo->variation->title), 'color') !== false)
                                            <li class="variant-item colorV" title="{{ $option->variation_option->title }}" data-title="{{ $option->variation_option->title }}" data-value="{{ $option->variation_option_id }}" data-variation="{{ $pvo->variation_id }}" role="radio"> &nbsp; 
                                             
                                                    <button type="button" class="colorbtn2" thumb="{{ $thumb }}" data-product_id="{{ $product->id }}" data-option_id="{{ $option->variation_option_id }}" 
                                                        style="@if($viewType=='square')border-radius:0px; @endif 
                                                        @if(strpos(strtolower(strtolower($option->variation_option->title)),'white') !== false || strpos(strtolower(strtolower($option->variation_option->code)),'#fff') !== false)border:2px solid #113c41 !important; @endif 
                                                        background:{{ $option->variation_option->code }}; 
                                                        @if($viewType=='circle')border-radius:25px; @endif
                                                        @if(strpos(strtolower(strtolower($option->variation_option->title)),'multi') !== false)background-image: linear-gradient(to right, #113c41,white,#e29d1b,blue,red); @endif">
                                                    </button>    
                                                </li>
                                            @else
                                                <li class="variant-item" title="{{ $option->variation_option->title }}" data-title="{{ $option->variation_option->title }}" data-value="{{ $option->variation_option_id }}" data-variation="{{ $pvo->variation_id }}" role="radio">
                                                    <label class="variant-label">{{ $option->variation_option->title }}</label>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                        </div>

                        
                        @if(request()->get('addToCart')=='1')
                            <form class="product-form" id="addToCartFormT" action="{{route('add-to-cart',app()->getLocale())}}">@csrf 
                                <div class="misc d-flex align-items-end justify-content-between mt-4">
                                    <div class="quantity d-flex align-items-center justify-content-between">
                                        <button type="button" class="qty-btn dec-qty"><img src="{{url('assets/img/icon/minus.svg')}}" alt="minus"></button>
                                        <input class="qty-input" type="number" name="quantity" value="1" min="0">
                                        <button type="button" class="qty-btn inc-qty"><img src="{{url('assets/img/icon/plus.svg')}}" alt="plus"></button>
                                    </div>
                                </div>

                                <div class="disMatchAlert" style="margin-top: 12px;color:red;"></div>
                                <input type="hidden" name="variation_option_id">
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="variation_id">
                                <input type="hidden" name="countVarient" value="{{ COUNT($variation_ids)}}">
                                <div class="product-form-buttons d-flex align-items-center justify-content-between mt-4">
                                    {{-- @if(is_stock_out($product->id))
                                        <button type="button" class=" btn-atc bg-warning" disabled>The product is out of the stock</button> --}}
                                    {{-- @else --}}
                                    {{-- <input type="hidden" name="product_id" value="{{$product->id}}"> --}}
                                    @if(is_stock_out($product->id))
                                        <button type="button" class="position-relative btn-atc bg-white text-danger border border-2 border-warning">Stock out</button>
                                    @else 
                                        <button type="button" class="position-relative btn-atc btn-add-to-cart loader" data-id="{{ $product->id }}">Add to Cart</button>
                                    @endif
                                    {{-- @endif --}}
                                    <a href="javaScript:;" class="product-wishlist action-wishlist" data-product_id="{{ $product->id }}">
                                        <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#00234D"></path>
                                        </svg>
                                    </a>
                                </div>
                            </form>
                        @else 
                            <p class="text-center p-3 bg-warning">
                                Order is Temporarily <b>Inactive</b>. <br> Please visit and order later
                            </p>
                            <button type="button" class="position-relative btn-atc" disabled><b>Add to Cart&nbsp;</b> Temporarily off</button>
                        @endif
                        

                        <div class="guaranteed-checkout">
                            <div class="filter-widget">
                                
                                <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom">
                                    Product category:
                                </div>
                                <ul class="filter-tags list-unstyled">
                                    @foreach ($cats as $cat)
                                        <li class="tag-item"><a href="{{ route('group', [app()->getLocale(), $cat->group->slug]) }}">{{ $cat->group->title }}</a></li>
                                    @endforeach

                                    @foreach ($sub_cats as $cat)
                                        <li class="tag-item"><a href="{{ route('group-in', [app()->getLocale(), $cat->inner_group->slug]) }}">{{ $cat->inner_group->title }}</a></li>
                                    @endforeach

                                    @foreach ($child_cats as $cat)
                                        <li class="tag-item"><a href="{{ route('child-in',[app()->getLocale(), $cat->child_group->slug]) }}" >{{ $cat->child_group->title }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="guaranteed-checkout">
                            <div class="filter-widget">
                                <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom">
                                    Product Tags:
                                </div>
                                <ul class="filter-tags list-unstyled">
                                    @foreach (explode(',',$product->tags) as $tag) 
                                      <li class="tag-item"> <a href="{{ route('products',[app()->getLocale()]).'?keyword='.$tag }}">{{ strtolower($tag) }}</a></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                </div>

                <div class="col-lg-12 col-md-12 col-12">
                    <div class="product-tab-section mt-100 row" data-aos="fade-up" data-aos-duration="700">
                        <div class="container">
                            <div class="tab-list product-tab-list">
                                <nav class="nav product-tab-nav">
                                    <a class="product-tab-link tab-link active" href="#pdescription" data-bs-toggle="tab">Product Details</a>
                                    @if($product->product_videos()->count()>0)
                                        <a class="product-tab-link tab-link" href="#videos" data-bs-toggle="tab">Product Videos</a>
                                    @endif
                                    <a class="product-tab-link tab-link" href="#size-guird" data-bs-toggle="tab">Product Size Guide</a>
                                    @if($product->product_terms()->count()>0)
                                    <a class="product-tab-link tab-link" href="#tersm" data-bs-toggle="tab">Reviews</a>
                                    @endif
                                </nav>
                            </div>
                            <div class="tab-content product-tab-content">
                                <div id="pdescription" class="tab-pane fade show active">
                                    <div class="row">
                                        <div class="col-lg-12 col-md-12 col-12">
                                            <div class="desc-content">
                                                <p class="text_16 mb-4">{!! $product->description !!}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div id="videos" class="tab-pane fade">
                                    <div class="desc-content">
                                        @foreach ($product->product_videos()->get() as $video)
                                        @if ($video->type=='video')
                                            <video width="100%" controls>
                                                <source src="/storage/{{ $video->video_link }}" type="video/mp4">
                                                <source src="/storage/{{ $video->video_link }}" type="video/ogg">
                                                Your browser does not support HTML video.
                                            </video>
                                        @else
                                            <iframe width="100%" height="315" src="{{ $video->video_link }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
                                        @endif
                                    @endforeach
                                    </div>
                                </div>
                                <div id="size-guird" class="tab-pane fade">
                                    <div class="desc-content">
                                        @if($product->size_chirt !=null)
                                        <a href="{{ $product->size_chirt->photo }}">
                                            <img src="{{ url('/storage/'.$product->size_chirt->photo) }}" style="max-width:100%">
                                        </a> 
                                        @endif
                                    </div>
                                </div>

                                <div id="tersm" class="tab-pane fade">
                                    <div class="review-area accordion-parent">
                                        <h4 class="heading_18 mb-3">Product tersm and conditions</h4>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @php
        $pieces = explode(" ", $product->title);
                                  
        $first_part = implode(" ", array_splice($pieces, 0, 2));
        $title = str_replace('-','',$first_part);
        
        $queries = \App\Models\Product::select('id','title','slug','thumbs','sale_price')
        ->where('status','1')->where('id','!=',$product->id)
        ->where(function($query) use ($title){
            $query->where('title', 'LIKE', '%'.$title.'%')
            ->orWhere('tags', 'LIKE', '%'.$title.'%')
            ;
        })->inRandomOrder()->limit(6)->get();

    @endphp
    @if($queries->count() >0)
        <div class="featured-collection-section mt-100 home-section overflow-hidden">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-heading">You may also like</h2>
                </div>

                <div class="product-container position-relative">
                    <div class="common-slider" data-slick='{
                        "slidesToShow": 4, 
                        "slidesToScroll": 1,
                        "dots": true,
                        "arrows": true,
                        "responsive": [
                        {
                            "breakpoint": 1281,
                            "settings": {
                            "slidesToShow": 3
                            }
                        },
                        {
                            "breakpoint": 768,
                            "settings": {
                            "slidesToShow": 2
                            }
                        }
                        ]
                        }'>

                        @foreach ($queries as $key=>$item)
                            <?php 
                                $old = old_price($item->id, $item->sale_price);
                                $new = product_price($item->id, $item->sale_price);
                                $percent = ( ($old - $new) / $old) * 100;
                            ?>
                        
                        <div class="new-item" data-aos="fade-up" data-aos-duration="{{$key+350}}">
                            <div class="product-card">
                                <div class="product-card-img">
                                    <a class="hover-switch" href="{{ route('product', [app()->getLocale(), $item->slug]) }}">
                                        <img class="primary-img" src="{{ $item->thumbs }}" alt="{{ $item->title }}">
                                    </a>

                                    <div class="product-card-action product-card-action-2">
                                        <a href="#quickview-modal" class="quickview-btn btn-primary" data-bs-toggle="modal" data-product_id="{{ $item->id }}">QUICKVIEW</a>
                                        <a href="#" class="addtocart-btn btn-primary" data-variation="{{$item->product_variation_options()->count()}}" data-product_id="{{ $item->id }}">ADD TO CART</a>
                                    </div>

                                    @if(is_stock_out($item->id))
                                        <div class="product-badge">
                                            <span class="p-2 bg-warning">Stock out</span>
                                        </div>
                                    @endif

                                    <a href="javaScript:;" class="wishlist-btn card-wishlist" data-product_id="{{ $item->id }}">
                                        <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                                fill="black" />
                                        </svg>
                                    </a>
                                </div>
                                <div class="product-card-details text-center">
                                    <h3 class="product-card-title"><a href="{{ url('product/'.$item->slug) }}">{{ $item->title }}</a>
                                    </h3>
                                    <div class="product-card-price">
                                        <span class="card-price-regular">{{ Session::get('user_currency')->currencySymbol }} {{ $new }}</span>
                                        @if ($new < $old )
                                            <span class="card-price-compare text-decoration-line-through">{{ Session::get('user_currency')->currencySymbol }} {{ $old }}</span>
                                        @endif
                                    </div>
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
    
@endsection

@push('style')
    <link rel="stylesheet" href="{{ asset('assets/zoom/two') }}/foundation.css" />
    <link rel="stylesheet" href="{{ asset('assets/zoom/two') }}/xzoom.css" />
    <link href="{{ asset('assets/zoom/two') }}/magnific-popup.css" type="text/css" rel="stylesheet" media="all" />
    <style> 
        /* .mfp-img, .elementor-lightbox-image{background: white} .xzoom-preview{background: white}  */
        button:disabled, button[disabled]{border: 1px solid #999999;background-color: #cccccc;color: #666666;}
        button:disabled:hover, button[disabled]:hover{border: 1px solid #999999; background-color: #696969; color: #ffffff;}
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('assets/zoom/two') }}/xzoom.min.js"></script>
    <script src="{{ asset('assets/zoom/two') }}/foundation.min.js"></script>
    <script src="{{ asset('assets/zoom/two') }}/setup.js"></script>
    <script type="text/javascript" src="{{ asset('assets/zoom/two') }}/magnific-popup.js"></script>

    <script>
        $( document ).ready(function() {
            var url = $("#url").val();

            $('.thumbImg').on('click', function(){
                var link = $(this).attr('src');
                var key = $(this).data('key');
                $('.xzoom5').attr('src',link);
                $('.xzoom5').attr('xoriginal',link);
                $(".slick-slide").removeClass("slick-current");
                $(".thumb"+key).addClass("slick-current");
            });


            $('.variant-item').on('click',function(){
                var id = $(this).data('value');
                var title = $(this).data('title');
                var variation_id = $(this).data('variation');

                $('#'+variation_id+' option:selected').attr('selected',false);
                $('#'+variation_id).find('.option'+id).attr('selected',true);
                $('.Vname'+variation_id).text(title);

                $('.disMatchAlert').html('');
                $('.stockResult').html('');

                let variants = $("select[name='variants'] :selected").map(function(i, el) {
                    return $(el).val();
                }).get();

                var myString = variants.toString();

                if(myString.charAt(0)==',' || myString[myString.length-1] == ',' || myString.indexOf(",,") > -1 ){
                    $('.variatinDiv').css('background','rgb(255 0 0 / 7%)')
                    // $('.variatinDiv').css('padding','1em')
                }else{
                    $.get(url+"/check-product-stock/{{ $product->id }}?variants="+variants, function(data, status){
                        $('.stockResult').html('<div class="mb-3 product-availability">'+data+'</div>');
                    });
                }
            });



            $(".btn-add-to-cart").on('click',function() {

                $(this).prop('disabled',true);
                let qty = $('[name=quantity]').val();
                $('.disMatchAlert').html('');
                let id = $(this).data('id');
                $(this).html('Adding to cart...');  $(this).prop('disabled',true);
                let variation_option_id = $("[name=variation_option_id]").val();
    
                
                let variants = $("select[name='variants'] :selected").map(function(i, el) {
                    return $(el).val();
                }).get();
               

                
				// console.log(url+"/{{ app()->getLocale() }}/add-to-cart?qty="+qty+'&id='+id+'&variants='+variants+'&variation_option_id='+variation_option_id);

				// return false;
                $.get(url+"/{{ app()->getLocale() }}/add-to-cart?qty="+qty+'&id='+id+'&variants='+variants+'&variation_option_id='+variation_option_id, function(data, status){
                    if(data[1]=='success') {
                        $('#notice').fadeIn('slow');
                        $('#notice').html('&#10003; The item added to cart successfully!!');
                        $('body').css('overflow','auto');
                        setTimeout(function(){ $('#notice').fadeOut('hide');}, 1500);

                        $(".addToCardNum").html(data[0]); 
                        $(".ajaxCard").load(url+'/my-cart-ajax');
                        //open mini cart modal
                        // $('.offcanvas').addClass('show');
                        $('.offcanvas').css('visibility','visible')
                        $('.selected-variation-item-name').text('');

                        $("[name=variants] :selected").attr("selected",false);
                        $('.stockResult').html('');
                        $('.showV').text('');
                    }
                    if(data[1]=='dismatch'){
                        $('.disMatchAlert').html(data[3]);
                    }
                    if(data[1]=='qty_dismatch'){
                        $('.disMatchQty').html(data[3]);
                    }
                });

                $(".btn-add-to-cart").text('Add to cart'); $(".btn-add-to-cart").prop('disabled',false);
            });

            $('.action-wishlist').on('click',function(){
                let id = $(this).data('product_id');
                $.get(url+"/add-to-wishlist/"+id, function( data ) {
                    $('#notice').fadeIn('slow');
                    $('#notice').html('&#10003; The item added to wishlist successfully!!');
                    setTimeout(function(){
                        $('#notice').fadeOut('hide');
                    }, 1000);
                });
            });

            $('.colorbtn2').on('click', function(){
                // $('.colorbtn2').css('border','1px solid grey !important;')
                let option_id = $(this).data('option_id');
                let product_id = $(this).data('product_id');
                $(this).css('border','2px solid black !important')
                // window.open(url+"/change-variant-photo/"+option_id+'/photo/'+product_id);
                $.get(url+"/change-variant-photo/"+option_id+'/photo/'+product_id, function(data, status){
                    $('.xzoom5').attr('src',data);
                    $('.xzoom5').attr('xoriginal',data);
                });
            })

            $('.colorV').on('click',function(){
                var id = $(this).data('value');
                $('[name=variation_option_id]').val(id);
            })

        })
    </script>
@endpush
