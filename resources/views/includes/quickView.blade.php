<?php $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
    $variation_ids = $product->product_variation_options()->select('variation_id')->distinct('variation_id')->get();
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();
    $colorPhotos = \DB::table('variation_option_photos')->where('product_id',$product->id)->select('thumbs','photo')->get();
?>

<div class="row">
    <div class="col-lg-6 col-md-12 col-12">
        <div class="product-gallery product-gallery-vertical d-flex">
            <div class="product-img-large">
                <div class="qv-large-slider img-large-slider common-slider" data-slick='{
                    "slidesToShow": 1,  "slidesToScroll": 1,
                    "dots": false,  "arrows": false,
                    "asNavFor": ".qv-thumb-slider" }'>
                    <div class="zoom img-large-wrapper" data-href="{{ $product->feature_photo}}">
                        <img src="{{ $product->feature_photo}}" alt="img">
                    </div>

                    @foreach($product->product_photos()->get() as $key=>$ph)
                        <div class="zoom img-large-wrapper" data-href="{{ $ph->photo }}">
                            <img src="{{ $ph->photo }}" class="zoomImg">
                        </div>
                    @endforeach
                    @foreach ($colorPhotos as $key=>$cp)
                        <div class="zoom img-large-wrapper" data-href="{{ $cp->photo}}">
                            <img src="{{ $cp->photo }}" class="zoomImg">
                        </div>
                    @endforeach              
                </div>
            </div>
            <div class="product-img-thumb">
                <div class="qv-thumb-slider img-thumb-slider common-slider" data-vertical-slider="true" data-slick='{
                    "slidesToShow": 5,   "slidesToScroll": 1,
                    "dots": false, "arrows": true,
                    "infinite": false,  "speed": 300,
                    "cssEase": "ease", "focusOnSelect": true,
                    "swipeToSlide": true,
                    "asNavFor": ".qv-large-slider" 
                }'>
                    
                    <div>
                        <div class="img-thumb-wrapper">
                            <img src="{{ $product->feature_photo }}" alt="img">
                        </div>
                    </div>

                    @foreach($product->product_photos()->get() as $key=>$ph)
                        <div>
                            <div class="img-thumb-wrapper">
                                <img src="{{ $ph->photo }}" alt="img">
                            </div>
                        </div>
                    @endforeach
                    @foreach ($colorPhotos as $key=>$cp)
                        <div>
                            <div class="img-thumb-wrapper">
                                <img src="{{ $cp->photo }}" alt="img">
                            </div>
                        </div>
                    @endforeach  
                
                </div>
                <div class="activate-arrows show-arrows-always arrows-white d-none d-lg-flex justify-content-between mt-3"></div>
            </div>
        </div>
    </div>
    <div class="col-lg-6 col-md-12 col-12">
        <div class="product-details ps-lg-4">

            <h2 class="product-title mb-3">{{ $product->title }}</h2>
            
            <?php
                $old = old_price($product->id, $product->sale_price);
                $new = product_price($product->id, $product->sale_price);
                $percent = ( ($old - $new) / $old) * 100;
                if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
                else $formatNumber = 3;
            ?>

            <div class="product-price-wrapper mb-4">
                <span class="product-price regular-price">{{ Session::get('user_currency')->currencySymbol }} {{ number_format(product_price($product->id, $product->sale_price),$formatNumber) }}</span>
                @if(product_price($product->id, $product->sale_price) < $product->sale_price)
                    @if ($new < $old )<del class="product-price compare-price ms-2">{{ Session::get('user_currency')->currencySymbol }} {{ number_format($old,$formatNumber) }}</del> @endif

                    @if($percent>0) <b style="text-decoration:line-through!important;"> {{round($percent, 2)}}%off </b> @endif
                @endif
                <span class="stockResult"></span>
            </div>
            
            <div class="product-sku product-meta mb-1"> <strong class="label">Product Code:</strong> {{$product->design_code}} </div>
       
            

            <div class="product-variant-wrapper">
                @foreach ($variation_ids as $key=>$pvo)
                    <?php $options = $product->product_variation_options()->where(['variation_id'=>$pvo->variation_id,'status'=>'1'])->select('variation_option_id')->get();?>
                    <div class="product-variant product-variant-other">
                        <select name="variants" id="{{ $pvo->variation_id }}" style="display:none"> <option value=""></option>
                            @foreach ($options as $key2=> $option) <option class="option{{ $option->variation_option_id }}" @if($key==0 && $key2==0)selected @endif
                                value="{{ $option->variation_option_id }}">{{ $option->variation_option->title }}</option>
                            @endforeach
                        </select>

                        <p class="text-dark"><b>{{$pvo->variation->title}}</b>: <span class="showV Vname{{ $pvo->variation_id }}">
                            @if($key==0){{ $options[0]->variation_option->title }} @endif
                        </span></p> 
                       
                        <ul class="variant-list list-unstyled d-flex align-items-center flex-wrap">
                            @foreach ($options as $key2=> $option)
                                @if(strpos(strtolower($pvo->variation->title), 'color') !== false)
                                    <li class="variant-item colorV" title="{{ $option->variation_option->title }}" data-title="{{ $option->variation_option->title }}" data-value="{{ $option->variation_option_id }}" data-variation="{{ $pvo->variation_id }}" role="radio"> &nbsp; 
                                        <button type="button" class="colorbtn2"  data-product_id="{{ $product->id }}" data-option_id="{{ $option->variation_option_id }}" 
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
                        
                
                <div class="disMatchAlert" style="padding:0.3em;margin-top:-25px;color:red;"></div>
                <input type="hidden" name="variation_option_id">
                <input type="hidden" name="variation_id">
                <input type="hidden" name="countVarient" value="{{ COUNT($variation_ids)}}">
             
            </div>
            
            <div class="misc d-flex align-items-end justify-content-between mt-4">
                <div class="quantity d-flex align-items-center justify-content-between">
                    <button class="qty-btn dec-qty"><img src="{{url('/assets/img/icon/minus.svg')}}" alt="minus"></button>
                    <input class="qty-input" type="number" name="quantity" value="1" min="0">
                    <button class="qty-btn inc-qty"><img src="{{url('/assets/img/icon/plus.svg')}}" alt="plus"></button>
                </div>
                
                <div class="message-popup d-flex align-items-center">
                    <span class="message-popup-icon">
                        <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.5 4.25V16.25H4.5V20.0703L5.71875 19.0859L9.25781 16.25H16.5V4.25H1.5ZM3 5.75H15V14.75H8.74219L8.53125 14.9141L6 16.9297V14.75H3V5.75ZM18 7.25V8.75H21V17.75H18V19.9297L15.2578 17.75H9.63281L7.75781 19.25H14.7422L19.5 23.0703V19.25H22.5V7.25H18Z" fill="black"/>
                    </svg>
                    </span>
                    <span class="message-popup-text ms-2">Message</span>
                </div>
            </div>

            <form class="product-form" action="#">
                <div class="product-form-buttons d-flex align-items-center justify-content-between mt-4">
                    @if(request()->get('addToCart')=='1')
                        @if(is_stock_out($product->id))
                            <button type="button" class="position-relative btn-atc bg-white text-danger border border-2 border-warning">Stock out</button>
                        @else
                            <button type="button" class="position-relative btn-atc btn-add-to-cart loader button" data-id="{{ $product->id }}">ADD TO CART</button>
                        @endif
                    @else 
                        <button type="button" class="position-relative btn-atc" disabled><b>Add to Cart &nbsp;</b> Temporarily off</button>
                    @endif
                    <a href="javaScript:;" class="product-wishlist action-wishlist" data-product_id="{{ $product->id }}">
                        <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z" fill="#00234D"></path>
                        </svg>
                    </a>
                </div>
                <div class="buy-it-now-btn mt-2">
                    <a href="{{route('product',[app()->getLocale(),$product->slug])}}" class="position-relative btn-atc btn-buyit-now">Product Details</a>
                </div>
            </form>                   

        </div>
    </div>
</div>


@push('styles')
    <style>
        .showV{font-size:20px;font-weight:bold;}
        .slick-list{min-height:540px }
        button:disabled, button[disabled]{border: 1px solid #999999;background-color: #cccccc;color: #666666;}
        button:disabled:hover, button[disabled]:hover{border: 1px solid #999999; background-color: #696969; color: #ffffff;}
    </style>
@endpush

{{-- <script src="{{asset('/assets/js/vendor.js')}}"></script> --}}
<script src="{{asset('/assets/js/main.js')}}"></script>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
<script src="{{asset('/assets/zoom/jquery.zoom.js')}}"></script>

<script>
    $(function(){
        $('.zoom').zoom({ on:'grab' });
      
	$('.product-img-thumb .slick-list').css('min-height','40vh')

        var url = $("#url").val();

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

        $('.colorbtn2').on('click', function(){
            // $('.colorbtn2').css('border','1px solid grey !important;')
            let option_id = $(this).data('option_id');
            let product_id = $(this).data('product_id');
            $(this).css('border','2px solid black !important')
            // window.open(url+"/change-variant-photo/"+option_id+'/photo/'+product_id);
            $.get(url+"/change-variant-photo/"+option_id+'/photo/'+product_id, function(data, status){
                $('.zoom img').attr('src',data);
                $('.zoom img').data('href',data);
            });
        })

        $('.action-wishlist').on('click',function(){
            let id = $(this).data('product_id');
            $.get(url+"/{{ app()->getLocale() }}/add-to-wishlist/"+id, function( data ) {
                $('#notice').fadeIn('slow');
                $('#notice').html('&#10003; The item added to wishlist successfully!!');
                setTimeout(function(){
                    $('#notice').fadeOut('hide');
                }, 1000);
            });
        });

        $('.colorV').on('click',function(){
            var id = $(this).data('value');
            $('[name=variation_option_id]').val(id);
        })

    });
</script>
