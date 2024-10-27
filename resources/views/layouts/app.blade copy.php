<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="preload" href="{{asset('assets/fonts/powerkit-icons.woff')}}" as="font" type="font/woff" crossorigin />

    @stack('meta')
    @include('includes.head')
    
    @stack('style')
</head>

<body style="flex-direction: column; display: contents;">
    <?php $category = \App\Models\Group::where('status','1')->orderBy('sort_by')->get();
        $policies = \DB::table('policy_types')->where('status','1')->orderBy('title')->get();
        $socials = \DB::table('social_media')->where('status','1')->get();
        $pages = \DB::table('page_post_types')->where('status','1')->orderBy('title')->get();
        $promotions = \DB::table('promotions')->where('status','1')->orderBy('title')->get();
        $seasons = \App\Models\Season::where('status','1')->orderBy('title')->get();
    ?>

    <div class="body-wrapper">
        <!-- top header start -->
        @include('includes.top-header')
        
        <!-- header start -->
        @include('includes.header')

        <main id="MainContent" class="content-for-layout">
            @yield('content')
        </main>

		@include('includes.footer')
        

        <!-- scrollup start -->
        <button id="scrollup">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>  
        </button>
		
        <!-- drawer menu start (mobile menu) -->
        @include('includes.mobile_nav')

        <!-- drawer cart start -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="drawer-cart">
            <div class="offcanvas-header border-btm-black">
                <h5 class="cart-drawer-heading text_16">Your Cart ( <span class="addToCardNum"> @if(Session::has('cart')) {{Session::get('cart')->count()}} @else 0 @endif </span> )</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body p-0 ajaxCard"> </div>
        </div>


        <!-- product quickview start -->
        <div class="modal fade" tabindex="-1" id="quickview-modal">
            <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body quickViewModal"> </div>
                </div>
            </div>
        </div>
        
        
        <input type="hidden" id="url" value="{{url('/')}}">
        <div id="notice">.......</div>

        <!-- all js -->
        <script src="/assets/js/vendor.js"></script>
        <script src="/assets/js/main.js"></script>
		
		<link rel="stylesheet" href="/assets/searchUI/jquery-ui.css">
		<script src="/assets/searchUI/jquery-ui.js"></script>
		
		<script>

		  $( function() {
            
            var url = $("#url").val();

            $('#searchProduct').autocomplete({
                source: "{{ url('autocomplete-search') }}",
                minLength: 1,
                select: function(event, ui){
                    $('#searchProduct').val(ui.item.value);
                    $('.widget.widget_pages li').css('display','flex')
                }
            }).data('ui-autocomplete')._renderItem = function(ul, item){
                return $("<li class='ui-autocomplete-row'></li>")
                .data("item.autocomplete", item)
                .append(item.label).appendTo(ul);
            };

            $('.search-form').on('keydown', function(event) {
                if (event.keyCode == 13) {
                    let keyword = $('#searchProduct').val();
                    window.location.replace(url+"/products?keyword="+keyword);
                }
            });
	
            $('.currency').on('change', function(){
                window.location = "{{ route('change-currency') }}?currency="+$(this).val();
            });
            

            $('.action-quickview, .quickview-btn').on('click', function(){
                var id = $(this).data('product_id');
                // window.open(url + "/modal-product/"+id);
                $.get( url + "/modal-product/"+id, function( data ) {
                    $( ".quickViewModal" ).html( data );
                });
                set_modal_background();
            });

          
            $('.header-cart').on('click', function(){
                $('.ajaxCard').load(url+'/my-cart-ajax');
                set_modal_background();
            });

            $('.offcanvas').on('click','.product-remove',function(){
                let id = $(this).data('id');
                let key = $(this).data('key');
                $.get(url+"/remove-cart/"+key+'/'+id, function( data ) {
                    $('.ajaxCard').load(url+'/my-cart-ajax');
                    $(".addToCardNum").html(data[0]);
                });
            });

            $('.offcanvas').on('click','.product-remove',function(){
                let id = $(this).data('id');
                let key = $(this).data('key');
                $.get(url+"/remove-cart/"+key+'/'+id, function( data ) {
                    $('.ajaxCard').load(url+'/my-cart-ajax');
                    $(".addToCardNum").html(data[0]);
                });
            });

            // change color base photos
            $(".colorbtn").on('mouseover', function (event) {
                let option_id = $(this).data('option_id');
                let product_id = $(this).data('product_id');
                $.get(url+"/change-variant-photo/"+option_id+'/thumbs/'+product_id, function(data, status){
                    $('.img'+product_id).attr('src',data);
                    // window.open(url+"/change-variant-photo/"+option_id+'/thumbs/'+product_id);
                });
            }).mouseleave(function(){
                let product_id = $(this).data('product_id');
                let thumb = $('.default_thumb'+product_id).val();
                $('.img'+product_id).attr('src',thumb);
            });

            $('.action-wishlist, .card-wishlist').on('click',function(){
                let id = $(this).data('product_id');
                $.get(url+"/add-to-wishlist/"+id, function( data ) {
                    $('#notice').fadeIn('slow');
                    $('#notice').html('&#10003; The item added to wishlist successfully!!');
                    setTimeout(function(){
                        $('.btn-add-to-cart').css('display','block');
                        $('#notice').fadeOut('hide');
                    }, 1000);
                });
            });

            $('.action-addtocart, .addtocart-btn').on('click',function(){
                let id = $(this).data('product_id');
                let variation = $(this).data('variation');
                if(variation >0){
                    $('#quickview-modal').modal('show');
                    $.get( url + "/modal-product/"+id, function( data ) {
                        $( ".quickViewModal" ).html( data );
                    });

                    set_modal_background();
                }else{
                    $.get(url + "/add-to-cart?qty=1&id="+id, function(data, status){
                        if(data[1]=='success') {
                            $('#notice').fadeIn('slow');
                            $('#notice').html('&#10003; The item added to wishlist successfully!!');
                            setTimeout(function(){
                                $('.btn-add-to-cart').css('display','block');
                                $('#notice').fadeOut('hide');
                            }, 1000);
                            $(".addToCardNum").html(data[0]); 
                        }
                        $(".action-addtocart").html('<svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z" fill="#00234D" /></svg>');
                    })
                }
                
            });
		  })


         function set_modal_background(){
            if($('.modal-backdrop').length >1){
                var elementsToRemove = $('.modal-backdrop');
                if (elementsToRemove.length > 1) {
                    elementsToRemove.slice(1).remove();
                }
            }
            $('body').css('overflow','auto');
         }

		</script>
        
        @stack('scripts')

    </div>
    
    <!-- Your Chat plugin code -->
    <div id="fb-customer-chat2" class="fb-customerchat"> </div>

    <script>
        var chatbox = document.getElementById('fb-customer-chat2');
        chatbox.setAttribute("page_id", "1786866638296873");
        chatbox.setAttribute("attribution", "biz_inbox");
    </script>
</body>

</html>