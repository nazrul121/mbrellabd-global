@extends('layouts.app')

@php
    $metas = \DB::table('metas')->where('pageFor','check-out');
    $meta = \DB::table('metas')->where(['pageFor'=>'check-out', 'type'=>'title']);

    $metaTitle = 'Mbrella | Check out';
    if($meta->count() >0){
        $metaTitle = $meta->where('type','title')->pluck('description')->first();
    }

    $default = DB::table('countries')->where('is_default','1')->first();
    $dollar = DB::table('dollar_rates')->where('country_id',session('user_currency')->id)->first();
    $bdt = DB::table('dollar_rates')->where('country_id','2')->first();


@endphp

@section('title', $metaTitle)

@section('content')

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    @foreach ($metas->get() as $meta)
        <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
    @endforeach
@endpush


@php
    $subtotal = $vats = array(); 
    $shippingType= \App\Models\Setting::where('type','deliveryCost_from')->pluck('value')->first();
    $customer = Auth::user();
    if(!empty($customer)){
        $customer = \App\Models\Customer::where('user_id',$customer->id)->first();
        if($customer == null || $customer->user->phone ==null) $customer = null;
    }

    $invoiceDiscountId = null;
    $checkInvoiceDiscount = \App\Models\Invoice_discount::where('status','1')->select('id','type','min_order_amount','discount_in','discount_value');
    $invoiceDiscount = $checkInvoiceDiscount->first();
    $invoice_discount = 0;

    $totalWeight = $weightLessItems = 0;

    $gateWays = \App\Models\Payment_gateway::where('status','1')->get();
@endphp

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home')}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" />
                    </g>
                </svg>
            </li>
            <li><a href="{{route('my-cart',app()->getLocale())}}">Cart</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none"  xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Checkout</li>
        </ul>
    </div>
</div>

<div class="checkout-page mt-100">
    <div class="container">
        <div class="checkout-page-wrapper">

            @if(Session::has('alert'))
                <p class="alert text-center text-danger alert-danger">{{ Session::get('alert') }}</p>
            @endif 

            @if(Session::has('cart') && Session::get('cart')->count() >0)
            <form class="row" id="checkout" action="{{ route('save-checkout-abroad') }}">@csrf
                <div class="col-xl-7 col-lg-7 col-md-7 col-12">
                    <div class="section-header mb-3"> <h2 class="section-heading">Check out</h2> </div>
                    
                    @if($customer !=null) <input type="hidden" name="customer_id" value="{{ $customer->id }}"> @endif

                    <div class="billingArea checkout-summary-area pt-3">
                        <h2 class="shipping-address-heading pb-1">Billing address </h2>
                       
                        <div class="shipping-address-form-wrapper">
                            @include('includes.checkout.billing-form') 
                        </div>

                        <div class="shipping-address-area">
                            <h2 class="shipping-address-heading pb-1">Billing address</h2>
                            <div class="form-checkbox d-flex align-items-center mt-4">
                                <input class="form-check-input mt-0" id="billingShippingSame" name="billing_shipping_same" type="checkbox" checked  value="1">
                                <label class="form-check-label ms-2" for="billingShippingSame">
                                    Same as shipping address
                                </label>
                            </div>
                        </div>
                    </div>


                    <div class="shippingArea shippingForm" style="display: none">
                        <h2 class="shipping-address-heading pb-1 pt-3">Shipping address</h2>
                        @include('includes.checkout.shipping-form')
                    </div>

                </div>
                <div class="col-xl-5 col-lg-5 col-md-5 col-12">
                    <div class="cart-total-area checkout-summary-area border-0 pt-0">

                        <div class="accordion-item border-0">
                            <h2 class="accordion-header">
                                <button type="button" class="accordion-button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapse1">Order summary</button>
                            </h2>
                            <div id="collapse1" class="accordion-collapse collapses collapse show" data-bs-parent="#orderSummary">
                                <div class="card-body">
                                    @foreach(Session::get('cart') as $key=>$cart)
                                        <?php if($cart->product->vat_type=='excluding'){
                                            $vat = ($cart->product->vat / 100) * product_price($cart->product_id, $cart->product->sale_price);
                                        }else $vat = null;

                                        if($cart->variation_option_id !=null){
                                            $thumb = \DB::table('variation_option_photos')->where(['product_id'=>$cart->product_id,'variation_option_id'=>$cart->variation_option_id])->pluck('thumbs')->first();
                                        }else $thumb = $cart->product->thumbs;

                                    
                                        if($cart->product->product_weight !=null){
                                            $totalWeight += $cart->product->product_weight->gross_weight * $cart->qty;
                                        }else $weightLessItems++;
                                        
                                        ?>    
                                        <div class="minicart-item d-flex">
                                            <div class="mini-img-wrapper">
                                                <a href="{{ route('product',[app()->getLocale(), $cart->product->slug]) }}">
                                                    <img class="mini-img" src="{{ $thumb }}" style="height:65px"> </a>
                                            </div>
                                            <div class="product-info">
                                                <h2 class="product-title"><a href="{{ route('product',[app()->getLocale(), $cart->product->slug]) }}">{{ $cart->product->title }}</a></h2>
                                                <small> @if($cart->product_combination_id !=null)
                                                    @foreach ($cart->product_combination()->get() as $key => $pComb)
                                                        @foreach (explode('~',$pComb->combination_string) as $string)
                                                            <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                            <b> {{ $v->variation->title.': '.$v->title }} </b>
                                                        @endforeach
                                                    @endforeach
                                                @endif </small>

                                                <p class="product-vendor">{{ Session::get('user_currency')->currencySymbol }} {{ number_format(product_price($cart->product_id, $cart->product->sale_price), 2) }} x {{ $cart->qty }}</p>
                                            </div>
                                        </div>
                                        <?php $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty;
                                        $vats[] = $vat * $cart->qty; ?>
                                    @endforeach

                                    @php
                                    $totalWeight = (float) $totalWeight;
                                    $getSlub = DB::table('dhl_zone_prices')->select('price')
                                        ->where('kg_from', '<=', $totalWeight)->where('kg_to', '>=', $totalWeight)
                                        ->where('zone', session('user_currency')->zone)->first();
                                    @endphp
                                </div>
                            </div>
                        </div>
                        @if($weightLessItems !=0)
                        <p class="alert text-center text-danger border border-danger border-1">One of the product item`s weight is missing</p>
                    @endif 

                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button type="button" class="accordion-button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapseFour">Delivery options</button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapses collapse show" data-bs-parent="#deliveryOption">
                                <div class="card-body">
                                    <div class="row">
                                        <ol class="shippingOutsite">
                                            <?php  $shippings = \DB::table('zones')->where(['status'=>'1', 'location'=>'abroad'])->get(); ?>
                                            @foreach ($shippings as $key=>$shipping)
                                                <label for="shipping{{ $key }}">
                                                    <input id="shipping{{ $key }}" @if($key==0)checked @endif type="radio" class="input-radio"  name="shipping"/> {{ $shipping->name}}
                                                    
                                                    <p>{{ 'Duration: '.$shipping->duration }}<br>
                                                    <span>{{ $shipping->description }}</p>
                                                </label>
                                            @endforeach
                                        </ol>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFive">
                                <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="true">Payment gateways</button>
                            </h2>
                            <div id="collapseFive" class="accordion-collapse collapses collapse show" data-bs-parent="#paymentOption">
                     
                                <div class="card-body">
                                    @foreach ($gateWays as $key=>$item)
                                        @if ($item->name_origin !='cash')
                                            <label for="gate_way{{$key}}">
                                                <input id="gate_way{{$key}}" type="radio"checked="checked" class="input-radio" name="payment_gateway" value="{{$item->id}}" data-origin="{{$item->name_origin}}">  {!! $item->name !!} 
                                                <div class="payment_box gate_way{{$key}}">
                                                    <p>{!! $item->description !!}</p>
                                                    @if($item->name_origin=='portPos')
                                                        <img class="portPos" src="{{ url('storage/images/portPos.webp') }}"> @endif
                                                </div>
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>

                       
                        <div class="card-body mb-4">
                            <div class="cart-total-box bg-transparent p-0">
                         
                                
                                <div class="subtotal-item shipping-box">
                                    <h4 class="subtotal-title">Subtotal (in {{ Session::get('user_currency')->currencySymbol }}):</h4>
                                    <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}  {{ number_format(array_sum($subtotal),2) }}</p>
                                </div>

                                <div class="subtotal-item shipping-box">
                                    <h4 class="subtotal-title">Subtotal (in BDT):</h4>
                                    <p class="subtotal-value">{{ $default->currencySymbol }}  {{ number_format(array_sum($subtotal) * Session::get('user_currency')->currencyValue,2) }}</p>
                                </div>

                                @if (Session::get('user_currency')->id !=1)
                                    <div class="subtotal-item shipping-box">
                                        <h4 class="subtotal-title">Subtotal (in USD):</h4>
                                        <p class="subtotal-value">$ {{ number_format(array_sum($subtotal) / $dollar->value,2) }}</p>
                                    </div>
                                @endif

                                <div class="subtotal-item discount-box">
                                    <h4 class="subtotal-title">Products Weight:</h4>
                                    <p class="text-center text-primary">  <b>{{  $totalWeight }}</b>KG  </p>
                                </div>

                             
                                {{-- {{ dd($getSlub, $totalWeight) }} --}}
                               
                                <div class="subtotal-item discount-box">
                                    <h4 class="subtotal-title">Shipping Charge:</h4>
                                    @if ($getSlub==null)
                                        <p class="text-center text-danger">Not <b>estimated </b> </p>
                                    @else
                                        <p class="text-right">$ {{  $getSlub->price }}</p>
                                    @endif
                                </div>

                                @if(session('user_currency')->id !=1)
                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Shipping Charge (in {{ session('user_currency')->currencySymbol }}):</h4>
                                        @if ($getSlub==null)
                                            <p class="text-center text-danger">Not <b>estimated </b> </p>
                                        @else
                                            <p class="text-right">{{ session('user_currency')->currencySymbol }} {{  number_format($getSlub->price * $dollar->value, 2) }}</p>
                                        @endif
                                    </div>
                                @endif

                                @if($totalWeight==0)
                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Product weight:</h4>
                                        <p class="text-center text-danger">Not <b>estimated </b> </p>
                                    </div>
                                @endif

                               
                                @if($getSlub!=null)
                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Total (in {{ Session::get('user_currency')->currencySymbol }}):</h4>
                                        @if($dollar !=null)
                                            <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }} 
                                                <span class="">{{ number_format( ( array_sum($subtotal) + array_sum($vats) + ($getSlub->price * $dollar->value) ) , 2) }}</span></p>
                                        @else
                                            <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }} 
                                                <span class="">{{ number_format( ( array_sum($subtotal) + array_sum($vats) + $getSlub->price ) , 2) }}</span></p>
                                        @endif
                                    </div>

                                    @if(session('user_currency')->id !=1) 
                                        <input type="hidden" value="{{ $getSlub->price * $dollar->value }}" name="shipping_charge"> 
                                    @else
                                        <input type="hidden" value="{{ $getSlub->price }}" name="shipping_charge"> 
                                    @endif

                                    
                                    <?php  $totalPlusVat = array_sum($subtotal) + array_sum($vats);
                                        $slubPlusDollar = $getSlub->price * $dollar->value;
                                        $totalInUSD = ($totalPlusVat + $slubPlusDollar) / $dollar->value;
                                    ?>

                                    <div class="subtotal-item discount-box">
                                        <h4 class="subtotal-title">Total (in USD):</h4>
                                        <p class="subtotal-value">$ <span class="">{{ number_format( $totalInUSD , 2) }}</span></p>
                                    </div>
                                
                                    <div class="subtotal-item shipping-box">
                                        <h4 class="subtotal-title">Total (in BDT):</h4>
                                        <p class="subtotal-value">{{ $default->currencySymbol }}  {{ number_format($totalInUSD * $bdt->value,2) }}</p>
                                    </div>
                                    
                                @endif

                                


                                <p class="p-3 bg-light border border-info">
                                    **Total BDT amount will be converted by your local bank's currency.<br>
                                    Actual may vary based on your bank's conversion rate.<br>
                                    **Any custom duties levied by delivery destination country customs authority, will be paid by the customer.
                                </p>

                               
                            </div>
                            
                            <div class="bg-light mt-4">
                                <input class="form-check-input" type="checkbox" name="agreement" id="agreed"  value="1">
                                <label for="agreed" id="agreedLabel">{{"I have read and agreed to the website`s"}}  <a href="{{ url('about/policy/terms-and-conditions') }}" target="_blank" class="text-primary"> terms and conditons</a>,
                                <a href="{{ url('about/policy/privacy-policy') }}" target="_blank" class="text-primary">Privacy policy</a> and <a href="{{ url('about/policy/refund-policy') }}" target="_blank" class="text-primary">Refund policy</a></label>

                            </div>
                        </div>

                        <input type="hidden" name='intotal' value="{{ array_sum($subtotal) + array_sum($vats) }}">
                        
                        {{-- <input class="input-promo-code" type="text" placeholder="Promo code" /> --}}
                       
                        @if ($getSlub==null || $totalWeight==0 || $weightLessItems !=0)
                            <a class="btn contact-submit-btn p-2 mb-5 float-end mt-md-4 border border-danger"> Place your order</a>
                        @else
                            <button type="submit"class="orderPlace review-submit-btn contact-submit-btn p-2 mb-5 float-end" disabled>
                                Place your order
                            </button>
                        @endif
                        
                        
                    </div>
                </div>
            </form>
            @else 
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="cart-total-area mt-2 mb-4">
                        <div class="cart-total-box mt-4">
                            <p class="shipping_text text-center">No Products to checkout</p>
                            <div class="d-flex justify-content-center mt-1">
                                <a href="{{ route('products',app()->getLocale()) }}" class="position-relative btn-primary text-uppercase">
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

@endsection

@push('style')
    <style>
        #agreedLabel{width: 93%; float: right;}
        #agreed{display: block; margin-top: 7px; width: auto; float: left;}
    </style>
@endpush

@push('scripts')
    @if(old('division'))
        <script>
            setTimeout(function() {
                get_district("{{ old('division') }}");
                get_cities("{{ old('district') }}");

                get_district("{{ old('shipping_division') }}");
                get_cities("{{ old('shipping_district') }}");

            }, 500);
        </script>
    @endif

    <script>
        $( document ).ready( function() {
            var url = $('#url').val();

            // document.getElementById('checkout').addEventListener('submit', function(event) {
            //     const radios = document.querySelectorAll('input[name="dhl"]');
            //     let isChecked = false;
            //     radios.forEach((radio) => {
            //         if (radio.checked) {
            //             isChecked = true;
            //         }
            //     });
                
            //     if (!isChecked) {
            //         event.preventDefault();
            //         alert('Please select an option before submitting.');
            //     }
            // });

            //check if billing address exist
            $('.checkBillingT').keyup(function(){
                let field = $(this).data('field');
                let fieldVal = $(this).val();
                // console.log(field+fieldVal);
                if(fieldVal.length>10){
                    $.ajax({ url:url+"/check-billing/"+ field+'/'+fieldVal, method:"get",
                        success:function(data){
                            if(data){
                                $('[name=address]').val(data.address);
                                $('[name=address]').prop('readonly',true);

                                $('[name=fname]').val(data.first_name);
                                $('[name=fname]').prop('readonly',true);

                                $('[name=lname]').val(data.last_name);
                                $('[name=lname]').prop('readonly',true);
                            
                                get_district(data.division_id);
                                get_cities(data.district_id);

                                $('[name=division] option[value="'+data.division_id+'"]').attr('selected', true);
                                setTimeout(function() {
                                    $('[name=phone]').val(data.phone);
                                    $('[name=email]').val(data.email);

                                    $('[name=district] option[value="'+data.district_id+'"]').attr('selected', true);
                                    $('[name=district]').prop('readonly',true);

                                    $('[name=city] option[value="'+data.city_id+'"]').attr('selected', true);
                                    $('[name=city]').prop('readonly',true);
                                }, 1000);


                                hideShow(data.city_id);

                                var intotal = $('[name=intotal]').val();
                                
                                $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+data.city_id+'/'+intotal, function(data, status){
                                    $('.zoneCosting').html(data[0]);
                                    let total = parseFloat($('[name=intotal]').val());
                                    $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                                    $('.shippingCost').text( parseFloat(data[1]));
                                    $('.shippingCostField').slideDown();
                                    $('[name=shipping]').prop('required',false);
                                })
                            }
                        }
                    });
                }else{
                    $('[name=address]').val('');
                    $('[name=fname]').val('');
                    $('[name=lname]').val('');
                    $('select[name="division"] option:selected').prop("selected", false);
                    $("[name=district]").html('')
                    $("[name=city]").html('')
                }
            })

            $('[name=shipping_address_id]').on('change', function(){
                var id = $(this).val();
                $('.bg').addClass('bg-1');
                $('.bg').removeClass('bg-5');
                $('.bg'+id).addClass('bg-5');

                $('.check').addClass('d-none');
                $('.check'+id).removeClass('d-none');
                var city_id = $(this).data('city');
                //alert(city_id);
                $('.shipping').slideUp();

                var intotal = $('[name=intotal]').val();
                
                $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+city_id+'/'+intotal, function(data, status){
                    $('.zoneCosting').html(data[0]);
                    let total = parseFloat($('[name=intotal]').val());
                    $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                    $('.shippingCost').text( parseFloat(data[1]));
                    $('.shippingCostField').slideDown();
                    $('[name=shipping]').prop('required',false);
                })
            })

            //get districts
            $('[name=division]').on('change',function(){
                $("[name=district]").html('') ; $("[name=city]").html('');
                $("[name=district]").append('<option value="">Districts</option>')
                let id =  $(this).val();
                hideShow(id);  get_district(id);
            });

            //get cities
            $('[name=district]').on('change',function(){
                $("[name=city]").html('')
                $("[name=city]").append('<option value="">Cities</option>')
                let id =  $(this).val();
                hideShow(id); get_cities(id);
            });

            // district form shipping selection
            $('[name=shipping_division]').on('change',function(){
                $("[name=shipping_district]").html('') ; $("[name=shipping_city]").html('');
                $("[name=shipping_district]").append('<option value="">Districts</option>')
                let div_id =  $(this).val();
                get_shipping_districts(div_id);

            });

            // cities form shipping districts
            $('[name=shipping_district]').on('change',function(){
                $("[name=shipping_city]").html('')
                $("[name=shipping_city]").append('<option value="">Cities</option>')
                let district_id =  $(this).val();
                get_shipping_cities(district_id);
            });

            $('.checkShippingT').keyup(function(){
                let field = $(this).data('field');
                let fieldVal = $(this).val();
                if(fieldVal.length>10){
                    $.ajax({ url:url+"/check-shipping/"+ field+'/'+fieldVal, method:"get",
                        success:function(data){
                            if(data){
                                $('[name=shipping_address]').val(data.address);
                                $('[name=shipping_fname]').val(data.fname);
                                $('[name=shipping_lname]').val(data.lname);
                                $('[name=shipping_phone]').val(data.phone);
                                $('[name=shipping_email]').val(data.email);

                                get_shipping_districts(data.division_id);
                                get_shipping_cities(data.district_id);

                                $('[name=shipping_division] option[value="'+data.division_id+'"]').attr('selected', true);
                                setTimeout(function() {
                                    $('[name=shipping_district] option[value="'+data.district_id+'"]').attr('selected', true);
                                    $('[name=shipping_city] option[value="'+data.city_id+'"]').attr('selected', true);
                                }, 310);

                                hideShow(data.city_id);
                                var intotal = $('[name=intotal]').val();
                                
                                $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+data.city_id+'/'+intotal, function(data, status){
                                    $('.zoneCosting').html(data[0]);
                                    let total = parseFloat($('[name=intotal]').val());
                              
                                    $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                                    $('.shippingCost').text( parseFloat(data[1]));
                                    $('.shippingCostField').slideDown();
                                    $('[name=shipping]').prop('required',false);
                                })
                            }
                        }
                    });
                }else{
                    $('[name=shipping_address]').val('');
                    $('[name=shipping_fname]').val('');
                    $('[name=shipping_lname]').val('');
                    $('select[name="shipping_division"] option:selected').prop("selected", false);
                    $("[name=shipping_district]").html('')
                    $("[name=shipping_city]").html('')
                }
            })

           
            $('[name=shipping]').on('change',function(){

            })

            $('[name=payment_gateway]').on('change', function(){
                var type = $(this).data('origin');
                if(type=='sslcommerz'){
                    $('.sslcommerz').slideDown();
                }else  $('.sslcommerz').slideUp();
            })

            $('[name=city]').on('change',function(){
                var id = '';
                var shipping_city = $('[name=shipping_city]').val();

                if(shipping_city ==null){  id = $(this).val();}
                else { id = shipping_city; }
                
                hideShow(id);

                var intotal = $('[name=intotal]').val();
             
                $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+id+'/'+intotal, function(data, status){
                    $('.zoneCosting').html(data[0]);
                    let total = parseFloat($('[name=intotal]').val());
                    $('.totalPayable').text((parseFloat(data[1]) + total).toFixed(2));
                    $('.shippingCost').text( parseFloat(data[1]));
                    $('.shippingCostField').slideDown();
                    $('[name=shipping]').prop('required',false);

                    $('[name=shipping]').prop('required',false);
         
                });
            })

            // $('[name=shipping_city]').on('change',function(){
            //     let id = $(this).val();
            //     hideShow(id);

            //     var intotal = $('[name=intotal]').val();
                
            //     $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+id+'/'+intotal, function(data, status){
            //         $('.zoneCosting').html(data[0])
            //         let total = parseFloat($('[name=intotal]').val());
            //         $('.totalPayable').text((data[1] + total).toFixed(2));
            //         $('.shippingCost').text( parseFloat(data[1]));
            //         $('.shippingCostField').slideDown();
            //         $('[name=shipping]').prop('required',false);
            //     });
            // })

            $('#billingShippingSame').on('change',function(){
                var defaultShippingCity = $('[name=shipping_address_id]:checked').data('city');
               
                if(defaultShippingCity >0){
                    $(".shipping").slideUp('fast');
                    var intotal = $('[name=intotal]').val();
                    
                    $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+defaultShippingCity+'/'+intotal, function(data, status){
                        $('.zoneCosting').html(data[0]);
                        let total = parseFloat($('[name=intotal]').val());
                        $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                        $('.shippingCost').text( parseFloat(data[1]));
                        $('.shippingCostField').slideDown();
                        $('[name=shipping]').prop('required',false);
                    })
                }
                
                if($(this).is(':checked')) {
                    $(this).val('1');
                    $('.shippingForm').slideUp();
                    $('[name=shipping_fname]').attr('required',false);
                    $('[name=shipping_lname]').attr('required',false);
                    $('[name=shipping_phone]').attr('required',false);
                    $('[name=shipping_division]').attr('required',false);
                    $('[name=shipping_district]').attr('required',false);
                    $('[name=shipping_city]').attr('required',false);
                    $('[name=shipping_address]').attr('required',false);
                }
                else {
                    $(this).val('0');
                    $('.shippingForm').slideDown();
                    $('[name=shipping_fname]').attr('required',true);
                    $('[name=shipping_lname]').attr('required',true);
                    $('[name=shipping_phone]').attr('required',true);
                    $('[name=shipping_division]').attr('required',true);
                    $('[name=shipping_district]').attr('required',true);
                    $('[name=shipping_city]').attr('required',true);
                    $('[name=shipping_address]').attr('required',true);
                }
            });

            $('[name=agreement]').on('change',function(){
                if($(this).is(':checked'))  $('.orderPlace').attr('disabled',false);
                else $('.orderPlace').attr('disabled',true);
            })

        });

        function hideShow(id){
            if(id.length <=0){
                $(".zoneCosting").html('');
                $(".shipping").slideDown('fast');
            }else {
                $(".shipping").slideUp('fast');
                $(".zoneCosting").html('Waiting<br/>for data selection....');
            }
        }

        var url = $('#url').val();

        function get_district(division_id){
            $.ajax({
                url:url+"/get-districts/"+ division_id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=district]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_cities(district_id){
            
            $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_shipping_cities(district_id){
            $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=shipping_city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_shipping_districts(div_id){
            $.ajax({ url:url+"/get-districts/"+ div_id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=shipping_district]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_outlet_customer(){
            var url = $('#url').val();
            $.get(url+'/check-outlet-customer', function(data, status){
                console.log(data);
                if(data.outletDiscount >0){
                    $('.outletDiscountArea').slideDown();
                    setTimeout(function(){
                        var total = parseFloat($('.totalPayable').text());
                        var discountAmt = data.outletDiscount.toFixed(3);
                        $('.outletDiscount').text(discountAmt);
                        var totalPay = total - data.outletDiscount.toFixed(3)
                        $('.totalPayable').text(totalPay.toFixed(2));
                    },100);
                }
            })
        }
    </script>

    {{-- remove delivery charge if invoice discount with free home delivery --}}
    {{-- @if($invoiceDiscount!=null && $invoiceDiscount->type=='free-delivery' && array_sum($subtotal) >= $invoiceDiscount->min_order_amount)
        <script>
        setTimeout(function(){
            var shippingCost = parseFloat($('.shippingCost').text());
            $('.shippingCostField').slideDown();
            var totalPayable = parseFloat($('.totalPayable').text());
            alert(totalPayable)
            $('.totalPayable').text((totalPayable-shippingCost).toFixed(2));
        },200)
        </script>
    @endif --}}
    @if(auth()->check() && auth()->user()->customer !=null)
        <script>
            setTimeout(function(){
             
                $.get("{{route('get-zone-from-city',[app()->getLocale(), $customer->city_id, array_sum($subtotal)] )}}", function(data, status){
                    $('.zoneCosting').html(data[0]);
                    let total = parseFloat($('[name=intotal]').val());
                  
                    $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                    $('.shippingCost').text( parseFloat(data[1]));
                    $('.shippingCostField').slideDown();
                    $('.shipping').slideUp();

                })
                
            },300)
        </script>
    @endif
@endpush
