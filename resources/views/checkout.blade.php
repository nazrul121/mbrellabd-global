@extends('layouts.app')

@php
    $metas = \DB::table('metas')->where('pageFor','check-out');
    $meta = \DB::table('metas')->where(['pageFor'=>'check-out', 'type'=>'title']);

    $metaTitle = 'Mbrella | Check out';
    if($meta->count() >0){
        $metaTitle = $meta->where('type','title')->pluck('description')->first();
    }
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
            <form class="row" action="{{ route('save-checkout') }}">@csrf
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

                                                <p class="product-vendor">{{ Session::get('user_currency')->currencySymbol }} {{ number_format(product_price($cart->product_id, $cart->product->sale_price) * $cart->qty, 2) }} x {{ $cart->qty }}</p>
                                            </div>
                                        </div>
                                        <?php $subtotal[] = product_price($cart->product_id, $cart->product->sale_price) * $cart->qty;
                                        $vats[] = $vat * $cart->qty; ?>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button type="button" class="accordion-button" aria-expanded="true" data-bs-toggle="collapse" data-bs-target="#collapseFour">Delivery options</button>
                            </h2>
                            <div id="collapseFour" class="accordion-collapse collapses collapse show" data-bs-parent="#deliveryOption">
                                <div class="card-body">
                                    <div class="row">
                                        <input type="hidden" name="shipping_type" value="{{ $shippingType }}">
            
                                        <ol class="zoneCosting"></ol>
                                        <ol class="shipping">
                                            <?php  $shippings = \DB::table('zones')->where(['status'=>'1', 'location'=>'inside'])->get(); ?>
                                            @foreach ($shippings as $key=>$shipping)
                                                <label for="shipping{{ $key }}">
                                                    <input id="shipping{{ $key }}" type="radio" class="input-radio"  name="shipping" data-cost="{{ number_format(deliveryCharge($shipping->delivery_cost - $invoice_discount), 3) }}" value="{{ $shipping->id }}" /> {{ $shipping->name}}
                                                    
                                                    <p>{{ 'Duration: '.$shipping->duration }}. &nbsp; Charge: {{ session()->get('user_currency')->currencySymbol }} {{ number_format(deliveryCharge($shipping->delivery_cost - $invoice_discount),3) }} <br>
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
                                        <label for="gate_way{{$key}}">
                                            <input id="gate_way{{$key}}" type="radio" @if($key==0)checked @endif class="input-radio" name="payment_gateway" value="{{$item->id}}" data-origin="{{$item->name_origin}}"> {!! $item->name !!} 
                                            <div class="payment_box gate_way{{$key}}">
                                                <p>{!! $item->description !!}</p>
                                                @if($item->name_origin=='sslcommerz')
                                                <img class="sslcommerz" src="{{ url('storage/images/sslcommer4zM2.webp') }}" style="display:none"> @endif

                                                @if($item->name_origin=='portPos')
                                                <img class="portPos" src="{{ url('storage/images/portPos.webp') }}" style="display:none"> @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                       
                        <div class="card-body mb-4">
                            <div class="cart-total-box bg-transparent p-0">
                              
                                @if ($checkInvoiceDiscount->count()==1 && array_sum($subtotal)>=$invoiceDiscount->min_order_amount)
                                    <?php $invoiceDiscountId = $invoiceDiscount->id;?>
                                    
                                    @if($invoiceDiscount!=null && $invoiceDiscount->type=='general')
                                        <?php  $invoice_discount = invoice_discount($invoiceDiscount,array_sum($subtotal)); ?>
                                        <span class="bg-warning p-1 text-white"> <b class="fa fa-check text-success"> You got a discount of {{ $invoiceDiscount->discount_value }}% = {{ $invoice_discount }} TK</b></span>
                                        <div class="subtotal-item shipping-box">
                                            <h4 class="subtotal-title">Invoice Discount:</h4>
                                            <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}  {{ number_format($invoice_discount,2) }}</p>
                                        </div>
                                    @elseif($invoiceDiscount!=null && $invoiceDiscount->type=='free-delivery')
                                        <?php  $invoice_discount = 0; ?>
                                        <span class="bg-warning p-1 text-white"> <b class="fa fa-check text-success"></b> You got a free delivery</b></span>
                                    @endif  
                                    
                                @endif

                               
                                
                                <div class="subtotal-item shipping-box">
                                    <h4 class="subtotal-title">Subtotal:</h4>
                                    <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }}  {{ number_format(array_sum($subtotal),2) }}</p>
                                </div>
                             
                                <div class="subtotal-item discount-box shippingCostField" style="display:none">
                                    <h4 class="subtotal-title">Shipping Charge:</h4>
                                    <p class="subtotal-value">
                                        {{ Session::get('user_currency')->currencySymbol }} <span class="shippingCost">0</span>
                                    </p>
                                </div>
                                
                                <div class="subtotal-item discount-box outletDiscountArea" style="display:none">
                                    <h4 class="subtotal-title">Membership card discount:</h4>
                                    <p class="subtotal-value">
                                        {{ Session::get('user_currency')->currencySymbol }} <span class="outletDiscount">0</span>
                                    </p>
                                </div>
                        
                                <div class="subtotal-item discount-box">
                                    <h4 class="subtotal-title">Total:</h4>
                                    <p class="subtotal-value">{{ Session::get('user_currency')->currencySymbol }} <span class="totalPayable">{{ number_format( (array_sum($subtotal) + array_sum($vats)) - $invoice_discount, 2) }}</span></p>
                                </div>
                            </div>
                            
                            <div class="bg-light mt-4">
                                <input class="form-check-input" type="checkbox" name="agreement" id="agreed"  value="1">
                                <label for="agreed" id="agreedLabel">{{"I have read and agreed to the website`s"}}  <a href="{{ url('about/policy/terms-and-conditions') }}" target="_blank" class="text-primary"> terms and conditons</a>,
                                <a href="{{ url('about/policy/privacy-policy') }}" target="_blank" class="text-primary">Privacy policy</a> and <a href="{{ url('about/policy/refund-policy') }}" target="_blank" class="text-primary">Refund policy</a></label>

                            </div>
                        </div>

                        <input type="hidden" name='intotal' value="{{ array_sum($subtotal) + array_sum($vats) }}">
                        <input type="hidden" name="invoice_discount" value="{{ $invoice_discount }}">
                        <input type="hidden" name="invoice_discount_id" value="{{ $invoiceDiscountId }}">


                        {{-- <input class="input-promo-code" type="text" placeholder="Promo code" /> --}}
                        <button type="submit"class="orderPlace review-submit-btn contact-submit-btn p-2 mb-5 float-end" disabled>
                            Place your order
                        </button>
                        
                    </div>
                </div>
            </form>
            @else 
                <div class="col-lg-12 col-md-12 col-12">
                    <div class="cart-total-box mt-4 mb-4">
                        <div class="cart-total-box mt-4">
                            <p class="shipping_text text-center">No <b>Products</b> to checkout</p>
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
    @if(!old('billing_shipping_same'))
        <script>
           setTimeout(() => {
                $('#billingShippingSame').prop('checked', false);
                $('#billingShippingSame').trigger('change');
           }, 1700);
        </script>
    @endif 

    @if(old('division'))
        <script>
            
            setTimeout(function() {
                get_district("{{ old('division') }}");
                get_cities("{{ old('district') }}");
            }, 1000);

            setTimeout(() => {
                var district = "{{ old('district')  }}";
                $("[name=district] option[value='" + district + "']").prop("selected", true);

                var city = "{{ old('city')  }}";
                $("[name=city] option[value='" + city + "']").prop("selected", true);
            }, 2000);
            
        </script>
    @endif

    @if(old('shipping_division'))
        <script>
            
            setTimeout(function() {
                get_shipping_districts("{{ old('shipping_division') }}");
                get_shipping_cities("{{ old('shipping_district') }}");
            }, 2200);

           

            setTimeout(() => {
                var shipping_district = "{{ old('shipping_district')  }}";
                $("[name=shipping_district] option[value='" + shipping_district + "']").prop("selected", true);

                var shipping_city = "{{ old('shipping_city')  }}";
                $("[name=shipping_city] option[value='" + shipping_city + "']").prop("selected", true);
            }, 3000);
            
        </script>
    @endif

    
    <script>
        $( document ).ready( function() {
            var url = $('#url').val();
            
            get_outlet_customer();

         
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

        
            $('[name=shipping]').on('change',function(){
                let cost = parseFloat($(this).data('cost'));
                let total = parseFloat($('[name=intotal]').val());
                $('.totalPayable').text((cost + total).toFixed(2));
                $('.shippingCost').text(cost);
                $('.shippingCostField').slideDown();
        
            })

            $('[name=payment_gateway]').on('change', function(){
                var type = $(this).data('origin');
                if(type=='sslcommerz'){
                    $('.sslcommerz').slideDown();
                }else  $('.sslcommerz').slideUp();

                if(type=='portPos'){
                    $('.portPos').slideDown();
                }else  $('.portPos').slideUp();
            })

            $('[name=city]').on('change',function(){
            
                var  id = $(this).val();
                var intotal = $('[name=intotal]').val();
                hideShow(id);
                
                if ($('#billingShippingSame').is(':checked')) {
                    $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+id+'/'+intotal, function(data, status){
                        $('.zoneCosting').html(data[0]);
                        let total = parseFloat($('[name=intotal]').val());
                        $('.totalPayable').text((parseFloat(data[1]) + total).toFixed(2));
                        $('.shippingCost').text( parseFloat(data[1]));
                        $('.shippingCostField').slideDown();
                        $('[name=shipping]').prop('required',false);

                        $('[name=shipping]').prop('required',false);
                        //remove delivery charge if invoice discount with free home delivery
                        @if($invoiceDiscount!=null && $invoiceDiscount->type=='free-delivery' && array_sum($subtotal) >= $invoiceDiscount->min_order_amount)
                            var shippingCost = parseFloat($('.shippingCost').text());
                            $('.shippingCost').text('-'+shippingCost);

                            var totalPayable = parseFloat($('.totalPayable').text());
                            $('.totalPayable').text((totalPayable-shippingCost).toFixed(2));
                            $('[name=shipping]').prop('required',false);
                        @endif
                    });
                }               
            })

            $('[name=shipping_city]').on('change',function(){
                let id = $(this).val();
                hideShow(id);

                var intotal = $('[name=intotal]').val();
                
                $.get(url+"/{{ app()->getLocale() }}/get-zone-from-city/"+id+'/'+intotal, function(data, status){
                    $('.zoneCosting').html(data[0])
                    let total = parseFloat($('[name=intotal]').val());
                    $('.totalPayable').text((data[1] + total).toFixed(2));
                    $('.shippingCost').text( parseFloat(data[1]));
                    $('.shippingCostField').slideDown();
                    $('[name=shipping]').prop('required',false);
                });
            })

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
                if ($('#billingShippingSame').is(':checked')) {
                    $(".zoneCosting").html('Waiting<br/>for data selection....');
                }
                
            }
        }

        var url = $('#url').val();

        function get_district(division_id){
            $.ajax({
                url:url+"/get-districts/"+ division_id, method:"get",
                success:function(data){
                    $("[name=district]").append('<option value="">Choose one</option>');
                    $.each(data, function(index, value){
                        $("[name=district]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_cities(district_id){
            
            $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
                success:function(data){
                    $("[name=city]").append('<option value="">Choose one</option>');

                    $.each(data, function(index, value){
                        $("[name=city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });

                    @if ($shippingType=='district')
                        $.get(url+"/district-delivery-info/"+district_id, function(data, status){
                            $('.shipping').html(data);
                        });
                    @endif
                }
            });
        }

        function get_shipping_cities(district_id){
            $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
                success:function(data){
                    $("[name=shipping_city]").append('<option value="">Choose one</option>');
                    $.each(data, function(index, value){
                        $("[name=shipping_city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_shipping_districts(div_id){
            $.ajax({ url:url+"/get-districts/"+ div_id, method:"get",
                success:function(data){
                    $("[name=shipping_district]").append('<option value="">Choose one</option>');
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
    @if($invoiceDiscount!=null && $invoiceDiscount->type=='free-delivery' && array_sum($subtotal) >= $invoiceDiscount->min_order_amount)
        <script>
            setTimeout(function(){
                var shippingCost = parseFloat($('.shippingCost').text());
                $('.shippingCostField').slideDown();
                let total = parseFloat($('[name=intotal]').val());
           
                $('.totalPayable').text((total-shippingCost).toFixed(2));
            },200)
        </script>
    @endif
    
    @if($customer !=null)
        <script>
            $.get("{{route('get-zone-from-city',[app()->getLocale(), $customer->city_id, array_sum($subtotal)] )}}", function(data, status){
                $('.zoneCosting').html(data[0]);
                let total = parseFloat($('[name=intotal]').val());
                
                $('.totalPayable').text( (parseFloat(data[1]) + total).toFixed(2));
                $('.shippingCost').text( parseFloat(data[1]));
                $('.shippingCostField').slideDown();
                
                $('.shipping').slideUp();
                $('[name=shipping]').prop('required',false);
                

                @if($invoiceDiscount!=null && $invoiceDiscount->type=='general')
                    invoice_discount = "{{ $invoice_discount }}"
                    $('.totalPayable').text( (parseFloat(( data[1]) + total ) - invoice_discount).toFixed(2));
                @endif
            })
                
        </script>
    @endif
@endpush
