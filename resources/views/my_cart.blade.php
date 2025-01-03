@extends('layouts.app')

<?php 
    $subtotal = $vats = array();
    // dd(Session::get('cart'));
    $metas = \DB::table('metas')->where('pageFor','my-cart');
    $meta = \DB::table('metas')->where(['pageFor'=>'my-cart', 'type'=>'title']);

    $metaTitle = 'Mbrella | My cart';
    if($meta->count() >0){
        $metaTitle = $metas->pluck('description')->first();
    }
?>

@section('title',$metaTitle)
    
@section('content')

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>  <li>Cart</li>
        </ul>
    </div>
</div>


<div class="cart-page mt-100">
    <div class="container">
        <div class="cart-page-wrapper">
            <div class="row">
                @if(Session::has('cart') && Session::get('cart')->where('country_id',session('user_currency')->id)->count()>0)
                <form class="col-lg-7 col-md-12 col-12 p-md-3 pt-md-0">@csrf
                    <table class="cart-table w-100 table">
                        <thead>
                          <tr>
                            <th class="cart-caption heading_18">Product</th>
                            <th class="cart-caption heading_18"></th>
                            <th class="cart-caption text-center heading_18 d-none d-md-table-cell">Quantity</th>
                            <th class="cart-caption text-end heading_18">Price</th>
                          </tr>
                        </thead>
            
                        <tbody>
                            @foreach(Session::get('cart') as $key=>$cart)
                            <?php if($cart->product->vat_type=='excluding'){
                                    $vat = ($cart->product->vat / 100) * product_price($cart->product_id, $cart->product->sale_price);
                                }else $vat = null;

                                if($cart->variation_option_id !=null){
                                    $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$cart->product_id,'variation_option_id'=>$cart->variation_option_id])->pluck('thumbs')->first();
                                }else $thumb = $cart->product->thumbs;
                            ?>
                            
                            <tr class="cart-item key{{$key}}">
                              <td class="cart-item-media pt-0">
                                <div class="mini-img-wrapper"> 
                                    <img class="mini-img" src="{{ $thumb }}" alt="img" style="height:@if($cart->product_combination_id==null)70px @else 75px @endif">
                                </div>                                  
                              </td>
                              <td class="cart-item-details">
                                <h2 class="product-title"><a href="{{ url('product').'/'.$cart->product->slug}}">{{$cart->product->title}}</a></h2>
                                <p class="product-vendor">
                                    @if($cart->product_combination_id !=null)
                                        @foreach ($cart->product_combination()->get() as $pComb)
                                            @foreach (explode('~',$pComb->combination_string) as $string)
                                                <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                <span class="p-1 bg-2 text-white">{{ $v->variation->title.': '.$v->title }} </span> &nbsp; 
                                            @endforeach
                                        @endforeach
                                    @endif
                                </p>                                   
                              </td>
                              <td class="cart-item-quantity">
                                <div class="quantity d-flex align-items-center justify-content-between">
                                    <button type="button" class="qty-btn dec-qty"><img src="/assets/img/icon/minus.svg" alt="minus"></button>
                                    <input class="qty-input" type="number" name="qty[]" value="{{ $cart->qty }}" title="Qty" min="0">
                                    <button type="button" class="qty-btn inc-qty"><img src="/assets/img/icon/plus.svg" alt="plus"></button>
                                </div>
                                <a href="#" data-key="{{$key}}" data-id="{{$cart->id}}" class="product-remove mt-2 text-danger">Remove</a>                           
                              </td>
                              <td class="cart-item-price text-end">
                                <div class="product-price pt-md-3">{{ Session::get('user_currency')->currencySymbol }}
                                    {{ number_format(product_price($cart->product_id, $cart->product->sale_price), 3) }}</div>                           
                              </td>                        
                            </tr> 
                            
                            <input type="hidden" name="cart_id[]" value="{{ $cart->id }}">
                            <?php $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty;
                                $vats[] = $vat * $cart->qty;
                            ?>
                            @endforeach
                            <tr class="cart-item">
                                <td colspan="4" class="text-end">
                                    <div class="checkout-promo-code">
                                        {{-- <label for="coupon_code">Coupon:</label> <input type="text" name="coupon_code" id="coupon_code" value="" placeholder="Coupon code">
                                        <button type="submit" class="button" name="apply_coupon" value="Apply coupon">Apply coupon</button> --}}
                                    </div>
                                    <button type="submit" class="btn-apply-code position-relative btn-default text-uppercase mt-3 p-3" name="update_cart" value="Update cart" aria-disabled="true">Update cart</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </form>
                <div class="col-lg-5 col-md-12 col-12">
                    <div class="cart-total-area">
                        <h3 class="cart-total-title d-none d-lg-block mb-0">Cart Totals</h4>
                        <div class="cart-total-box mt-4">
                            <div class="subtotal-item subtotal-box">
                                <h4 class="subtotal-title">Subtotals:</h4>
                                <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}</span>{{ number_format(array_sum($subtotal), 3) }}</p>
                            </div>
                            <div class="subtotal-item shipping-box">
                                <h4 class="subtotal-title">Ex. Tax:</h4>
                                <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}</span>{{ number_format(array_sum($vats), 3) }}</p>
                            </div>
                            <div class="subtotal-item discount-box">
                                <h4 class="subtotal-title">Total Items:</h4>
                                <p class="subtotal-value">{{COUNT($subtotal)}}</p>
                            </div>
                            <hr />
                            <div class="subtotal-item discount-box">
                                <h4 class="subtotal-title">Total:</h4>
                                <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}</span>{{ number_format(array_sum($subtotal) + array_sum($vats), 3) }}</p>
                            </div>
                            <p class="shipping_text">Shipping & taxes calculated at checkout</p>
                            <div class="d-flex justify-content-center mt-4">
                                <a href="{{ route('checkout',app()->getLocale()) }}" class="position-relative btn-primary text-uppercase">
                                    Procced to checkout
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @else
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="cart-total-area">
                        <div class="cart-total-box mt-4">
                            <p class="shipping_text text-center">No Product added</p>
                            <div class="d-flex justify-content-center mt-1">
                                <a href="{{route('products',app()->getLocale())}}" class="position-relative btn-primary text-uppercase">
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
        $(function(){
            var url = $("#url").val();
            $('.product-remove').on('click',function(){
                let id = $(this).data('id');
                let key = $(this).data('key');
                $.get(url+"/remove-cart/"+key+'/'+id, function( data ) {
                    $(".key"+key).remove();
                    $(".addToCardNum").html(data[0]);
                    location.reload();
                });

            });
        })
    </script>
@endpush
