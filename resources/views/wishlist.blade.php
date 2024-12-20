@extends('layouts.app')

@php
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();
    $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
    $metas = \DB::table('metas')->where('pageFor','wishlist');
    $meta = \DB::table('metas')->where(['pageFor'=>'wishlist', 'type'=>'title']);

    $metaTitle = 'Wishlist | '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $meta->pluck('description')->first();
    }
@endphp

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush


@section('title', $metaTitle)

@section('content')

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4"><path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" /></g>
                </svg>
            </li>  <li>Wishlist</li>
        </ul>
    </div>
</div>


<div class="wishlist-page mt-100">
    <div class="wishlist-page-inner">
        <div class="container">
            <div class="section-header d-flex align-items-center justify-content-between flex-wrap">
                <h2 class="section-heading">My Wishlist</h2> 
            </div>
            <hr>
            <div class="row">
                @foreach($wishlists as $key=>$cart)
                <?php
                    // dd($product->sale_price);
                    $old = old_price($cart->product_id, $cart->product->sale_price);
                    $new = product_price($cart->product_id, $cart->product->sale_price);
                    $percent = ( ($old - $new) / $old) * 100;
                    if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
                    else $formatNumber = 3;
                ?>

                <div class="col-lg-3 col-md-6 col-6 row{{$key}}" data-aos="fade-up" data-aos-duration="700">
                    <div class="product-card">
                        <div class="product-card-img">
                            <a class="hover-switch" href="{{ url('product').'/'.$cart->product->slug}}">
                                <img class="primary-img" src="{{ url('storage').'/'.$cart->product->thumbs }}" alt="product-img">
                            </a>
                            @if($percent >0)
                                <div class="product-badge">
                                    <span class="badge-label badge-percentage rounded">-{{ number_format($percent) }} %</span>
                                </div>
                            @endif

                            @if(is_stock_out($cart->product_id))
                                <div class="product-badge">
                                    <span class="p-2 bg-warning">Stock out</span>
                                </div>
                            @endif

                            <div class="product-card-action product-card-action-2 justify-content-center">
                                <a href="#quickview-modal" class="action-card action-quickview" data-product_id="{{ $cart->product_id }}" data-bs-toggle="modal">
                                    <svg width="26" height="26" viewBox="0 0 26 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10 0C15.5117 0 20 4.48828 20 10C20 12.3945 19.1602 14.5898 17.75 16.3125L25.7188 24.2812L24.2812 25.7188L16.3125 17.75C14.5898 19.1602 12.3945 20 10 20C4.48828 20 0 15.5117 0 10C0 4.48828 4.48828 0 10 0ZM10 2C5.57031 2 2 5.57031 2 10C2 14.4297 5.57031 18 10 18C14.4297 18 18 14.4297 18 10C18 5.57031 14.4297 2 10 2ZM11 6V9H14V11H11V14H9V11H6V9H9V6H11Z" fill="#00234D" />
                                    </svg>
                                </a>

                                <a href="javascript:;" class="action-card remove-wishlist" title="Remove form Wishlist" data-id="{{ $cart->id }}" data-key="{{ $key }}" >
                                    <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"fill="#FF0000" />
                                    </svg>
                                </a>

                                <a href="#" class="action-card action-addtocart" data-product_id="{{ $cart->product_id }}" data-variation="{{$cart->product->product_variation_options()->count()}}" >
                                    <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="#00234D" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                        <div class="product-card-details">
                            <ul class="color-lists list-unstyled d-flex align-items-center">
                                @foreach ($cart->product->product_variation_options()->where('variation_id',$listViewVariationId)->get() as $pvo)
                                    <?php $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$cart->product_id,'variation_option_id'=>$pvo->variation_option_id])->pluck('thumbs')->first(); ?>
                                    <button type="button" title="{{ $pvo->variation_option->title }}" class="colorbtn" thumb="/storage/{{ $thumb }}" data-product_id="{{ $cart->product_id }}" data-option_id="{{ $pvo->variation_option_id }}" 
                                        style="@if($viewType=='square')border-radius:0px; @endif 
                                        @if(strpos(strtolower(strtolower($pvo->variation_option->title)),'white') !== false || strpos(strtolower(strtolower($pvo->variation_option->code)),'#fff') !== false)border:2px solid #113c41 !important; @endif 
                                        background:{{ $pvo->variation_option->code }}; 
                                        @if($viewType=='circle')border-radius:12px; @endif
                                        @if(strpos(strtolower(strtolower($pvo->variation_option->title)),'multi') !== false)background-image: linear-gradient(to right, #113c41,white,#e29d1b,blue,red); @endif">
                                    </button> &nbsp; 
                                    @endforeach
                            </ul>
                            <h3 class="product-card-title">
                                <a href="{{ url('product').'/'.$cart->product->slug}}">{{ $cart->product->title }}</a>
                            </h3>
                            <input type="hidden" name="cart_id[]" value="{{ $cart->id }}">
                            <div class="product-card-price">
                                <span class="card-price-regular">{{ Session::get('user_currency')->symbol. product_price($cart->product_id, $cart->product->sale_price)}}</span>
                                @if ($new < $old )
                                    <span class="card-price-compare text-decoration-line-through amount">{{ Session::get('user_currency')->symbol }}{{ number_format($old,$formatNumber)}}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

                @if($wishlists->count() < 1)
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="cart-total-area">
                        <div class="cart-total-box m-4">
                            <p class="shipping_text text-center">No Product added</p>
                            <div class="d-flex justify-content-center mt-1">
                                <a href="{{url('products')}}" class="position-relative btn-primary text-uppercase">
                                    Continue Shopping
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>            
</div>            

@endsection

@push('scripts')
    <script>
        var url = $("#url").val();
        $('.remove-wishlist').on('click',function(){
            var id = $(this).data('id');
            var key = $(this).data('key');
            $.get(url+'/remove-wishlist/'+id, function(data, status){
                $('.addWishNum').html(data[0]);
                $('.row'+key).remove();
            });

        });
    </script>
@endpush
